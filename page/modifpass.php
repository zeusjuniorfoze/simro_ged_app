<?php
require_once('conect.php');
// on verifie si la session de pass est bien activée
if (!$_SESSION['recup']) {
    header('location: connexionInscription.php');
}
$email = $_SESSION['recup'];
if (isset($_POST['login'])) {
    $erreu = "";
    $erre = "";
    if (isset($_POST['pass']) and !empty($_POST['pass']) and isset($_POST['pass2']) and !empty($_POST['pass2'])) {
        $pass1 = $_POST['pass']; // Mot de passe en clair
        $pass2 = $_POST['pass2']; // Mot de passe en clair
        if ($pass1 != $pass2) {
            $erre = "Les mots de passe ne correspondent pas !!";
        } else {
            $hashedPass = password_hash($pass1, PASSWORD_BCRYPT); // Hachage du mot de passe validé
            $sql = $con->prepare("UPDATE utilisateur SET MOT_DE_PASSE=? WHERE EMAIL=?");
            $sql->execute(array($hashedPass, $email));
            header('location: connexionInscription.php');
        }
    } else {
        $erreu = "Veuillez remplir tous les champs";
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <link rel="stylesheet" href="boostrap/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="css/pass.css">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="boxicons/css/boxicons.min.css" rel="stylesheet">

    <title>MODIFICATION</title>
</head>

<body>
    <div>
        <nav class="cc-nav navbar nav-dark ">
            <div class="container-fluid">
                <!-- image du logo -->
                <a class="navbar-brand py-1 mx-3" href="home.php">
                    <img src="img/simro_logo.PNG" alt="" width="100" height="100" class="d-inline-block align-text-top">
                </a>
                <!-- liste des element du  menue -->
                <h1 class="fw-bolder"><i style="color: #120cef;">SIMRO</i><i style="color: #f3940b;">GED</i></h1>
                <ul class="navbar-nav ms-auto mb-2lg-0">
                    <li><a style=" font-size: 25px; " href="connexionInscription.php" class="btn btn-lg btn-in my-2"><i class='bx bx-user-plus'></i> S'INSCRIRE</a></li>
                    <li><a style=" font-size: 25px; " href="connexionInscription.php" class="btn btn-con"><i class='bx bx-log-in'></i> SE CONNECTER</a></li>
                </ul>
            </div>
        </nav>
    </div>
    <div>
        <section>
            <form action="" method="POST">
                <h2>MODIFICATION DU MOT DE PASS </h2>

                <fieldset class="b">
                    <label form="pass">Veillez Entrer Votre Nouveau Mot De Pass </label><br>
                    <input type="Password" name="pass" id="pass"><br>
                    <label form="pass">Veillez Confirmert Le Mot De Pass </label><br>
                    <input type="Password" name="pass2" id="pass2">
                    <?php if (isset($erreu)) { ?>
                        <p class='erreu'><?php echo "$erreu"; ?></p>
                    <?php } ?>

                    <?php if (isset($erre)) { ?>
                        <p class='erreu'><?php echo "$erre"; ?></p>
                    <?php } ?>
                </fieldset><br>
                <div class="button-container">
                    <button type="button" class="cancel-button" onclick="window.location.href = 'password_oublier.php';">Annuler</button>
                    <button type="submit" class="submit-button" name="login">Modifier</button>
                </div>

            </form>
        </section>
    </div>
</body>

</html>