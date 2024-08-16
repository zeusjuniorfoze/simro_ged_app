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
        <h1 class="text-center mb-4">Gérer les Utilisateurs</h1>

        <!-- Formulaire pour ajouter un utilisateur -->
        <div class="card mb-4">
            <div class="card-header">
                Ajouter un Utilisateur
            </div>
            <div class="card-body">
                <form>
                    <div class="mb-3">
                        <label for="username" class="form-label">Nom d'utilisateur</label>
                        <input type="text" class="form-control" id="username" placeholder="Nom d'utilisateur">
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" placeholder="exemple@domaine.com">
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Mot de passe</label>
                        <input type="password" class="form-control" id="password" placeholder="Mot de passe">
                    </div>
                    <div class="mb-3">
                        <label for="role" class="form-label">Rôle</label>
                        <select class="form-select" id="role">
                            <option value="Admin">Admin</option>
                            <option value="Utilisateur">Utilisateur</option>
                            <option value="Gestionnaire de documents">Gestionnaire de documents</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary">Ajouter</button>
                </form>
            </div>
        </div>

        <!-- Tableau pour afficher les utilisateurs -->
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nom d'utilisateur</th>
                    <th>Email</th>
                    <th>Rôle</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <!-- Exemples de lignes, à remplacer par des données dynamiques -->
                <tr>
                    <td>1</td>
                    <td>Admin1</td>
                    <td>admin1@exemple.com</td>
                    <td>Admin</td>
                    <td>
                        <a href="#" class="btn btn-warning btn-sm">Modifier</a>
                        <a href="#" class="btn btn-danger btn-sm">Supprimer</a>
                    </td>
                </tr>
                <tr>
                    <td>2</td>
                    <td>User2</td>
                    <td>user2@exemple.com</td>
                    <td>Utilisateur</td>
                    <td>
                        <a href="#" class="btn btn-warning btn-sm">Modifier</a>
                        <a href="#" class="btn btn-danger btn-sm">Supprimer</a>
                    </td>
                </tr>
                <!-- Fin des exemples -->
            </tbody>
        </table>
    </div>

    <script src="../boostrap/js//bootstrap.min.js">
    </script>
</body>

</html>