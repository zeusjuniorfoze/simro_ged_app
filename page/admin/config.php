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
    <title>Configurer l'Application</title>
    <link rel="stylesheet" href="../boostrap//css//bootstrap.min.css">

<body>
    <div class="container my-5">
        <h1 class="text-center mb-4">Configurer l'Application</h1>

        <!-- Formulaire de configuration -->
        <form>
            <div class="mb-4">
                <label for="siteName" class="form-label">Nom du Site</label>
                <input type="text" class="form-control" id="siteName" placeholder="Nom du site">
            </div>
            <div class="mb-4">
                <label for="adminEmail" class="form-label">Email de l'Administrateur</label>
                <input type="email" class="form-control" id="adminEmail" placeholder="email@domaine.com">
            </div>
            <div class="mb-4">
                <label for="passwordPolicy" class="form-label">Politique de Mot de Passe</label>
                <textarea class="form-control" id="passwordPolicy" rows="3" placeholder="Description des règles de mot de passe"></textarea>
            </div>
            <div class="mb-4">
                <label for="securityLevel" class="form-label">Niveau de Sécurité</label>
                <select class="form-select" id="securityLevel">
                    <option value="low">Faible</option>
                    <option value="medium">Moyen</option>
                    <option value="high">Élevé</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Enregistrer les Modifications</button>
        </form>
    </div>

    <script src="../boostrap/js//bootstrap.min.js">
    </script>
</body>

</html>