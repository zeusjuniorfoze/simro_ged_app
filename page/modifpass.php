<?php
require_once('conect.php');

// Vérifier si la session de récupération est active
if (!isset($_SESSION['recup'])) {
    header('location: connexionInscription.php');
    exit();
}

$email = $_SESSION['recup'];
$erreu = "";
$erre = "";
$erres = "";

if (isset($_POST['login'])) {
    if (!empty($_POST['pass']) && !empty($_POST['pass2'])) {
        $pass1 = $_POST['pass'];
        $pass2 = $_POST['pass2'];

        if ($pass1 !== $pass2) {
            $erre = "Les mots de passe ne correspondent pas !";
        } else {
            $hashedPass = password_hash($pass1, PASSWORD_BCRYPT);
            $sql = $con->prepare("UPDATE utilisateur SET MOT_DE_PASSE=? WHERE EMAIL=?");
            $sql->execute(array($hashedPass, $email));
            echo "<script> alert('Votre Mot De Pass As Ete Modifier Avec Success');window.location.href = 'connexionInscription.php';</script>";
            // header('location: connexionInscription.php');
            exit();
        }
    } else {
        $erreu = "Veuillez remplir tous les champs.";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <link rel="stylesheet" href="boostrap/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="css/pass.css">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="boxicons/css/boxicons.min.css" rel="stylesheet">

    <title>Modification du Mot de Passe</title>
</head>

<body>
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
    </nav><br><br><br><br><br>

    <div class="container my-5">
        <section class="bg-light p-5 rounded shadow">
            <form action="" method="POST" class="needs-validation" novalidate>
                <h2 class="mb-4">Modification du Mot de Passe</h2>

                <div class="mb-3">
                    <label for="pass" class="form-label">Veuillez entrer votre nouveau mot de passe :</label>
                    <input type="password" name="pass" id="pass" class="form-control" required>
                    <div class="invalid-feedback">
                        Veuillez entrer un mot de passe.
                    </div>
                </div>

                <div class="mb-3">
                    <label for="pass2" class="form-label">Veuillez confirmer le mot de passe :</label>
                    <input type="password" name="pass2" id="pass2" class="form-control" required>
                    <div class="invalid-feedback">
                        Veuillez confirmer votre mot de passe.
                    </div>
                </div>

                <?php if (!empty($erreu)) { ?>
                    <div class="alert alert-warning" role="alert">
                        <?php echo $erreu; ?>
                    </div>
                <?php } ?>

                <?php if (!empty($erre)) { ?>
                    <div class="alert alert-danger" role="alert">
                        <?php echo $erre; ?>
                    </div>
                <?php } ?>

                <?php if (!empty( $erres)) { ?>
                    <div class="alert alert-success" role="alert">
                        <?php echo  $erres; ?>
                    </div>
                <?php } ?>
                <div class="d-flex justify-content-between">
                    <button type="button" class="btn btn-lg cancel-button" onclick="window.location.href = 'password_oublier.php';">Annuler</button>
                    <button type="submit" class="btn btn-lg submit-button" name="login">Modifier</button>
                </div>
            </form>
        </section>
    </div>

    <script src="bootstrap/js/bootstrap.bundle.min.js"></script>
    <script>
        // Bootstrap validation script
        (function() {
            'use strict'

            var forms = document.querySelectorAll('.needs-validation')

            Array.prototype.slice.call(forms).forEach(function(form) {
                form.addEventListener('submit', function(event) {
                    if (!form.checkValidity()) {
                        event.preventDefault()
                        event.stopPropagation()
                    }

                    form.classList.add('was-validated')
                }, false)
            })
        })()

        // Validation des mots de passe
        document.querySelector('form').addEventListener('submit', function(e) {
            var pass1 = document.getElementById('pass').value;
            var pass2 = document.getElementById('pass2').value;

            if (pass1 !== pass2) {
                e.preventDefault();
                alert("Les mots de passe ne correspondent pas !");
            }
        });
    </script>
</body>

</html>