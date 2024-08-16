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
        echo "<script>alert(' Les Mots De Passe Ne Correspondents Pas ! ');
        window.location.href = '../connexionInscription.php';
        </script>";
        exit(); // Stoppe l'exécution si les mots de passe ne correspondent pas
    }

    // Hachage du mot de passe après validation
    $mot_de_passe_hache = password_hash($mot_de_passe, PASSWORD_DEFAULT);

    // Vérification si l'utilisateur existe déjà
    $sql = $con->prepare("SELECT * FROM utilisateur WHERE EMAIL = ?");
    $sql->execute([$email]);
    $recup = $sql->fetch();

    if ($recup && $recup['EMAIL'] === $email) {
        echo "<script>alert(' Entree Un Email Valide ! ');
        window.location.href = '../connexionInscription.php';
        </script>";
    } else {
        // Préparation de la requête SQL pour l'insertion
        $stmt = $con->prepare("INSERT INTO utilisateur (NOM_UTIL, EMAIL, MOT_DE_PASSE, ROLE) VALUES (?, ?, ?, ?)");
        $stmt->execute([$prenom, $email, $mot_de_passe_hache, "user"]);

        // Redirection après inscription réussie
        echo '<script>';
        echo 'if(confirm("Inscription réussie. Appuyez sur OK puis sur login pour vous connecter.")){';
        echo 'window.location.href = "../connexionInscription.php";';
        echo '}';
        echo '</script>';
    }
} catch (PDOException $e) {
    echo "Erreur d'inscription: " . $e->getMessage();
}

// Fermeture de la connexion
$con = null;
