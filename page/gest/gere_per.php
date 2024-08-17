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
    <title>Gérer les Permissions</title>
    <link rel="stylesheet" href="../boostrap/css/bootstrap.min.css"></head>
<body>
    <div class="container my-5">
    <a href="gest.php" class="btn btn-danger btn-lg">Retour</a>
        <h1 class="text-center mb-4">Gérer les Permissions</h1>

        <!-- Tableau pour gérer les permissions des documents -->
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>ID Document</th>
                    <th>Titre du Document</th>
                    <th>Utilisateur</th>
                    <th>Permission</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <!-- Exemples de lignes, à remplacer par des données dynamiques -->
                <tr>
                    <td>1</td>
                    <td>Rapport Annuel</td>
                    <td>User1</td>
                    <td>
                        <select class="form-select form-select-sm">
                            <option value="lecture">Lecture</option>
                            <option value="écriture">Écriture</option>
                            <option value="lecture-écriture">Lecture et Écriture</option>
                        </select>
                    </td>
                    <td>
                        <a href="#" class="btn btn-primary btn-sm">Enregistrer</a>
                    </td>
                </tr>
                <tr>
                    <td>2</td>
                    <td>Contrat Client</td>
                    <td>User2</td>
                    <td>
                        <select class="form-select form-select-sm">
                            <option value="lecture">Lecture</option>
                            <option value="écriture">Écriture</option>
                            <option value="lecture-écriture">Lecture et Écriture</option>
                        </select>
                    </td>
                    <td>
                        <a href="#" class="btn btn-primary btn-sm">Enregistrer</a>
                    </td>
                </tr>
                <!-- Fin des exemples -->
            </tbody>
        </table>
    </div>

    <script src="../boostrap/js/bootstrap.min.js">
    </script></body>
</html>
