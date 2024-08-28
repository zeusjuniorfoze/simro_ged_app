<?php
try {
    require_once('../conect.php');

    // Récupération des données du formulaire
    $prenom = $_POST['prenom'];
    $email = $_POST['email'];
    $mot_de_passe = $_POST['mot_de_passe'];
    $mot_de_passe_confirme = $_POST['confirm_mot_de_passe'];

    // Vérification si les mots de passe correspondent
    if ($mot_de_passe !== $mot_de_passe_confirme) {
        $_SESSION['error'] = 'Les mots de passe ne correspondent pas !';
        header('Location: ../connexionInscription.php');
        exit(); // Stoppe l'exécution si les mots de passe ne correspondent pas
    }

    // Hachage du mot de passe après validation
    $mot_de_passe_hache = password_hash($mot_de_passe, PASSWORD_DEFAULT);

    // Vérification si l'utilisateur existe déjà
    $sql = $con->prepare("SELECT * FROM utilisateur WHERE EMAIL = ?");
    $sql->execute([$email]);
    $recup = $sql->fetch();

    if ($recup && $recup['EMAIL'] === $email) {
        $_SESSION['error'] = 'Cet email est déjà utilisé !';
        header('Location: ../connexionInscription.php');
    } else {
        // Préparation de la requête SQL pour l'insertion
        $stmt = $con->prepare("INSERT INTO utilisateur (NOM_UTIL, EMAIL, MOT_DE_PASSE, ROLE) VALUES (?, ?, ?, ?)");
        $stmt->execute([$prenom, $email, $mot_de_passe_hache, "user"]);

        $_SESSION['success'] = 'Inscription réussie. Vous pouvez maintenant vous connecter.';
        header('Location: ../connexionInscription.php');
    }
    exit();
} catch (PDOException $e) {
    $_SESSION['error'] = "Erreur d'inscription: " . $e->getMessage();
    header('Location: ../connexionInscription.php');
    exit();
}

// Fermeture de la connexion
$con = null;
