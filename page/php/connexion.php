<?php
try {
    require_once('../conect.php');

    // Récupération des données du formulaire
    $role = $_POST['role'];
    $email = $_POST['email'];
    $password = $_POST['mot_de_passe'];

    // // Affichage pour le débogage
    // echo "<p>Rôle : $role</p>";
    // echo "<p>Email : $email</p>";

    // Recherche de l'utilisateur dans la base de données en fonction de l'email et du rôle
    $sql = $con->prepare("SELECT MOT_DE_PASSE, ID_UTILISATEUR, NOM_UTIL FROM UTILISATEUR WHERE EMAIL = ? AND ROLE = ?");
    $sql->execute([$email, $role]);
    $user = $sql->fetch();

    // Affichage des résultats pour le débogage
    if ($user) {
        // echo "<p>Mot de passe stocké : " . $user['MOT_DE_PASSE'] . "</p>";
    } else {
        echo "<p>Aucun utilisateur trouvé avec ces informations.</p>";
    }

    // Vérification du mot de passe si l'utilisateur est trouvé
    if ($user && password_verify($password, $user['MOT_DE_PASSE'])) {
        // // L'utilisateur est authentifié avec succès
        $_SESSION['user_id'] = $user['ID_UTILISATEUR'];
        $_SESSION['role'] = $role;
        $_SESSION['nom_util'] = $user['NOM_UTIL'];
        // Redirection en fonction du rôle
        if ($role === 'admin') {
            header('Location: ../admin/admin.php'); // Redirige vers le tableau de bord administrateur
        } elseif ($role === 'gest') {
            echo "gest";
            header('Location: ../gest/gest.php'); // Redirige vers le tableau de bord gestionnaire
        } elseif ($role === 'user') {
            echo "user";
            header('Location: ../user/user.php'); // Redirige vers le tableau de bord utilisateur
        } else {
            echo "<script>alert('Role invalide !');</script>";
        }
    } else {
        // L'utilisateur n'est pas trouvé ou le mot de passe est incorrect
        echo "<script>alert('Informations incorrectes ou compte inexistant !');</script>";
        echo "<script>window.location.href = '../connexionInscription.php';</script>";
        exit();
    }
} catch (PDOException $e) {
    // En cas d'erreur, affichage de l'erreur
    echo "<script>alert('Erreur lors de l\'authentification: " . $e->getMessage() . "');</script>";
    echo "<script>window.location.href = '../connexionInscription.php';</script>";
    exit();
}
