<?php


try {
    require_once('../conect.php');

    // Récupération des données du formulaire
    $role = $_POST['role'];
    $email = $_POST['email'];
    $password = $_POST['mot_de_passe'];

    // Recherche de l'utilisateur dans la base de données en fonction de l'email et du rôle
    $sql = $con->prepare("SELECT MOT_DE_PASSE, ID_UTILISATEUR, NOM_UTIL FROM UTILISATEUR WHERE EMAIL = ? AND ROLE = ?");
    $sql->execute([$email, $role]);
    $user = $sql->fetch();

    // Vérification du mot de passe si l'utilisateur est trouvé
    if ($user && password_verify($password, $user['MOT_DE_PASSE'])) {
        // L'utilisateur est authentifié avec succès
        $_SESSION['user_id'] = $user['ID_UTILISATEUR'];
        $_SESSION['role'] = $role;
        $_SESSION['nom_util'] = $user['NOM_UTIL'];

        // Redirection en fonction du rôle
        if ($role === 'admin') {
            header('Location: ../admin/admin.php');
        } elseif ($role === 'gest') {
            header('Location: ../gest/gest.php');
        } elseif ($role === 'user') {
            header('Location: ../user/user.php');
        } else {
            $_SESSION['error'] = 'Role invalide !';
            header('Location: ../connexionInscription.php');
        }
        exit();
    } else {
        // L'utilisateur n'est pas trouvé ou le mot de passe est incorrect
        $_SESSION['error'] = 'Informations incorrectes ou compte inexistant !';
        header('Location: ../connexionInscription.php');
        exit();
    }
} catch (PDOException $e) {
    // En cas d'erreur, affichage de l'erreur
    $_SESSION['error'] = "Erreur lors de l'authentification: " . $e->getMessage();
    header('Location: ../connexionInscription.php');
    exit();
}
