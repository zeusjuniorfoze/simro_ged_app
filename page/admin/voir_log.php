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
    <title>Voir les Logs</title>
    <link rel="stylesheet" href="../boostrap//css//bootstrap.min.css">
</head>

<body>
    <div class="container my-5">
        <h1 class="text-center mb-4">Journaux d'Audit</h1>

        <!-- Table pour afficher les logs -->
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Utilisateur</th>
                    <th>Action</th>
                    <th>Document ID</th>
                    <th>Date et Heure</th>
                </tr>
            </thead>
            <tbody>
                <!-- Exemples de lignes, à remplacer par des données dynamiques -->
                <tr>
                    <td>1</td>
                    <td>Admin1</td>
                    <td>Ajout d'un document</td>
                    <td>15</td>
                    <td>2024-08-07 14:32:00</td>
                </tr>
                <tr>
                    <td>2</td>
                    <td>User2</td>
                    <td>Consultation d'un document</td>
                    <td>10</td>
                    <td>2024-08-07 13:45:00</td>
                </tr>
                <!-- Fin des exemples -->
            </tbody>
        </table>

        <!-- Pagination (facultatif) -->
        <nav aria-label="Page navigation">
            <ul class="pagination justify-content-center">
                <li class="page-item"><a class="page-link" href="#">Précédent</a></li>
                <li class="page-item"><a class="page-link" href="#">1</a></li>
                <li class="page-item"><a class="page-link" href="#">2</a></li>
                <li class="page-item"><a class="page-link" href="#">3</a></li>
                <li class="page-item"><a class="page-link" href="#">Suivant</a></li>
            </ul>
        </nav>
    </div>

    <script src="../boostrap/js//bootstrap.min.js">
    </script>

</html>