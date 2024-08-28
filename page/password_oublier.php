<?php
require_once 'conect.php';

$erreu = "";
$erre = "";

if (isset($_POST['login'])) {
    if (isset($_POST['email']) && !empty($_POST['email'])) {
        $email = htmlspecialchars(trim($_POST['email']));

        $sql = $con->prepare("SELECT * FROM utilisateur WHERE EMAIL = ?");
        $sql->execute(array($email));

        if ($sql->rowCount() > 0) {
            $_SESSION['recup'] = $email;
            header('location: modifpass.php');
        } else {
            $erreu = "Votre Compte N'existe Pas, Réessayez !";
        }
    } else {
        $erre = "Veuillez entrer votre email !";
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
    <title>Récupération</title>
</head>

<body>
    <nav class="cc-nav navbar nav-dark ">
        <div class="container-fluid">
            <!-- image du logo -->
            <a class="navbar-brand py-1 mx-3" href="home.php">
                <img src="img/simro_logo.PNG" alt="" width="100" height="100" class="d-inline-block align-text-top">
            </a>
            <!-- liste des element du  menue -->
            <h1 class="fw-bolder"><i style="color: #120cef;">SIMRO</i><i style="color: #f3940b;">CHAT</i></h1>
            <ul class="navbar-nav ms-auto mb-2lg-0">
                <li><a style=" font-size: 25px; " href="connexionInscription.php" class="btn btn-lg btn-in my-2"><i class='bx bx-user-plus'></i> S'INSCRIRE</a></li>
                <li><a style=" font-size: 25px; " href="connexionInscription.php" class="btn btn-con"><i class='bx bx-log-in'></i> SE CONNECTER</a></li>
            </ul>
        </div>
    </nav><br><br><br><br><br><br>

    <div class="container my-5">
        <section class="bg-light p-5 rounded shadow">
            <form action="" method="POST" class="needs-validation" novalidate>
                <h2 class="mb-4">Retrouver Votre Compte</h2>

                <div class="mb-3">
                    <label for="email" class="form-label">Veuillez entrer l'email associé à votre compte :</label>
                    <input type="email" name="email" id="email" class="form-control" placeholder="Entrez votre email" required>
                    <div class="invalid-feedback">
                        Veuillez entrer une adresse email valide.
                    </div>
                </div>

                <?php if (!empty($erreu)) { ?>
                    <div class="alert alert-danger" role="alert">
                        <?php echo $erreu; ?>
                    </div>
                <?php } ?>

                <?php if (!empty($erre)) { ?>
                    <div class="alert alert-warning" role="alert">
                        <?php echo $erre; ?>
                    </div>
                <?php } ?>

                <div class="d-flex justify-content-between">
                    <button type="button" class="btn cancel-button" onclick="window.location.href = 'connexionInscription.php';">Annuler</button>
                    <button type="submit" class="btn submit-button " name="login">Rechercher</button>
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
    </script>
</body>

</html>