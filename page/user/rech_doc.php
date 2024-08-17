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
    <title>Rechercher des Documents</title>
    <link rel="stylesheet" href="../boostrap/css/bootstrap.min.css"></head>
<body>
    <div class="container my-5">
    <a href="user.php" class="btn btn-danger btn-lg">Retour</a>
        <h1 class="text-center mb-4">Rechercher des Documents</h1>

        <!-- Formulaire de recherche -->
        <form class="mb-4">
            <div class="row g-3">
                <div class="col-md-4">
                    <label for="searchTitle" class="form-label">Titre</label>
                    <input type="text" class="form-control" id="searchTitle" placeholder="Titre du document">
                </div>
                <div class="col-md-4">
                    <label for="searchCategory" class="form-label">Catégorie</label>
                    <select class="form-select" id="searchCategory">
                        <option value="">Toutes</option>
                        <option value="Rapports">Rapports</option>
                        <option value="Contrats">Contrats</option>
                        <option value="Notes">Notes</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label for="searchDate" class="form-label">Date d'Ajout</label>
                    <input type="date" class="form-control" id="searchDate">
                </div>
            </div>
            <div class="mt-4 text-center">
                <button type="submit" class="btn btn-primary">Rechercher</button>
            </div>
        </form>

        <!-- Résultats de la recherche -->
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Titre</th>
                    <th>Catégorie</th>
                    <th>Date d'Ajout</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <!-- Exemples de résultats de recherche -->
                <tr>
                    <td>1</td>
                    <td>Rapport Annuel</td>
                    <td>Rapports</td>
                    <td>2024-08-01</td>
                    <td>
                        <a href="#" class="btn btn-primary btn-sm">Télécharger</a>
                        <a href="#" class="btn btn-info btn-sm">Voir</a>
                    </td>
                </tr>
                <tr>
                    <td>2</td>
                    <td>Contrat Client</td>
                    <td>Contrats</td>
                    <td>2024-07-15</td>
                    <td>
                        <a href="#" class="btn btn-primary btn-sm">Télécharger</a>
                        <a href="#" class="btn btn-info btn-sm">Voir</a>
                    </td>
                </tr>
                <!-- Fin des exemples -->
            </tbody>
        </table>
    </div>

    <script src="../boostrap/js/bootstrap.min.js">
    </script></body>
</html>
