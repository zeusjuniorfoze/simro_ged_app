<?php
 require_once('../conect.php');
// on verifie si la session de pass est bien active ou pas
if (!$_SESSION['user_id']) {
    header('location: ../connexionInscription.php');
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tableau de Bord - Administrateur</title>
    <link rel="stylesheet" href="../boxicons/css/boxicons.min.css">
    <link rel="stylesheet" href="../boostrap//css//bootstrap.min.css">
    <link rel="stylesheet" href="../css//nav.css">
</head>

<body>
<nav class="cc-nav navbar nav-dark ">
        <div class="container-fluid">
            <!-- image du logo -->
            <a class="navbar-brand py-1 mx-3" href="#">
                <img src="../img/simro_chat.PNG" alt="" width="100" height="100" class="d-inline-block align-text-top">
            </a>
            <!-- liste des element du  menue -->
            <h1 class="fw-bolder"><i style="color: #120cef; font-size: 40px;  ">SIMRO</i><i style="color: #f3940b;">-GED</i></h1>
            <ul class="navbar-nav ms-auto mb-2lg-0">
                <li><a style=" font-size: 25px; " href="../deconnexion.php" class="btn btn-con"><i class='bx bx-log-in'> </i> DECONNEXION</a></li>
            </ul>
        </div>
    </nav><br><br><br><br>
    <div class="container my-5">
        <h1 class="text-center mb-4"> Bienvenue sur votre page <strong><?php echo $_SESSION['nom_util'] ?></strong></h1>
        </h1>
        <div class="row">
            <div class="col-md-3">
                <div class="card text-white bg-primary mb-3">
                    <div class="card-body">
                        <h5 class="card-title">Gérer les Utilisateurs</h5>
                        <p class="card-text">Ajouter, modifier ou supprimer des utilisateurs.</p>
                        <a href="gere.php" class="btn btn-light">Gérer</a>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-white bg-secondary mb-3">
                    <div class="card-body">
                        <h5 class="card-title">Surveillance du Système</h5>
                        <p class="card-text">Surveiller l'activité et les journaux d'audit.</p>
                        <a href="voir_log.php" class="btn btn-light">Voir les Logs</a>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-white bg-danger mb-3">
                    <div class="card-body">
                        <h5 class="card-title">Gérer les permissions</h5>
                        <p class="card-text">Ajouter, modifier ou supprimer des permissions.</p>
                        <a href="atri_per.php" class="btn btn-light">Voir les permission</a>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-white bg-success mb-3">
                    <div class="card-body">
                        <h5 class="card-title">Paramètres de l'Application</h5>
                        <p class="card-text">Configurer les paramètres généraux de l'application.</p>
                        <a href="config.php" class="btn btn-light">Configurer</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="../boostrap//js//bootstrap.min.js"></script>
</body>

</html>