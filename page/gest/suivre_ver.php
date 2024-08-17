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
    <title>Suivre les Versions</title>
    <link rel="stylesheet" href="../boostrap//css//bootstrap.min.css"></head>
<body>
    <div class="container my-5">
    <a href="gest.php" class="btn btn-danger btn-lg">Retour</a>
        <h1 class="text-center mb-4">Suivre les Versions des Documents</h1>

        <!-- Tableau pour afficher les versions des documents -->
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Titre</th>
                    <th>Version</th>
                    <th>Modifié Par</th>
                    <th>Date de Modification</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <!-- Exemples de lignes, à remplacer par des données dynamiques -->
                <tr>
                    <td>1</td>
                    <td>Rapport Annuel</td>
                    <td>v2.0</td>
                    <td>Gestionnaire</td>
                    <td>2024-08-05</td>
                    <td>
                        <a href="#" class="btn btn-info btn-sm">Voir</a>
                        <a href="#" class="btn btn-danger btn-sm">Restaurer</a>
                    </td>
                </tr>
                <tr>
                    <td>2</td>
                    <td>Contrat Client</td>
                    <td>v1.1</td>
                    <td>Admin1</td>
                    <td>2024-07-20</td>
                    <td>
                        <a href="#" class="btn btn-info btn-sm">Voir</a>
                        <a href="#" class="btn btn-danger btn-sm">Restaurer</a>
                    </td>
                </tr>
                <!-- Fin des exemples -->
            </tbody>
        </table>
    </div>

    <script src="../boostrap//js//bootstrap.min.js">
    </script></body>
</html>
