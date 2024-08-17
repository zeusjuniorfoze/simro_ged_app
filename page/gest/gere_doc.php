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
    <title>Gérer les Documents</title>
    <link rel="stylesheet" href="../boostrap//css//bootstrap.min.css">
</head>

<body>
    <div class="container my-5">
    <a href="gest.php" class="btn btn-danger btn-lg">Retour</a>
        <h1 class="text-center mb-4">Gérer les Documents</h1>

        <!-- Formulaire pour ajouter un document -->
        <div class="card mb-4">
            <div class="card-header">
                Ajouter un Document
            </div>
            <div class="card-body">
                <form>
                    <div class="mb-3">
                        <label for="docTitle" class="form-label">Titre du Document</label>
                        <input type="text" class="form-control" id="docTitle" placeholder="Titre du document">
                    </div>
                    <div class="mb-3">
                        <label for="docCategory" class="form-label">Catégorie</label>
                        <select class="form-select" id="docCategory">
                            <option value="Rapports">Rapports</option>
                            <option value="Contrats">Contrats</option>
                            <option value="Notes">Notes</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="docFile" class="form-label">Fichier</label>
                        <input type="file" class="form-control" id="docFile">
                    </div>
                    <button type="submit" class="btn btn-primary">Ajouter</button>
                </form>
            </div>
        </div>

        <!-- Tableau pour afficher les documents -->
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
                <!-- Exemples de lignes, à remplacer par des données dynamiques -->
                <tr>
                    <td>1</td>
                    <td>Rapport Annuel</td>
                    <td>Rapports</td>
                    <td>2024-08-01</td>
                    <td>
                        <a href="#" class="btn btn-warning btn-sm">Modifier</a>
                        <a href="#" class="btn btn-danger btn-sm">Supprimer</a>
                    </td>
                </tr>
                <tr>
                    <td>2</td>
                    <td>Contrat Client</td>
                    <td>Contrats</td>
                    <td>2024-07-15</td>
                    <td>
                        <a href="#" class="btn btn-warning btn-sm">Modifier</a>
                        <a href="#" class="btn btn-danger btn-sm">Supprimer</a>
                    </td>
                </tr>
                <!-- Fin des exemples -->
            </tbody>
        </table>
    </div>

    <script src="../boostrap//js//bootstrap.min.js">
    </script>
</body>

</html>