<?php
// Inclusion du fichier de connexion à la base de données
require_once('../conect.php');

// Vérification si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    // Si l'utilisateur n'est pas connecté, redirection vers la page de connexion/inscription
    header('Location: ../connexionInscription.php');
    exit();
}

// Initialiser les messages d'alerte
$alertMessage = '';
$alertType = '';

// Gestion de l'insertion des utilisateurs
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Récupération des données du formulaire
    $username = $_POST['username']; // Nom d'utilisateur
    $email = $_POST['email'];       // Email
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT); // Hash du mot de passe pour sécuriser les données
    $role = $_POST['role'];         // Rôle de l'utilisateur

    // Requête SQL pour insérer un nouvel utilisateur dans la base de données
    $sql = "INSERT INTO utilisateur (NOM_UTIL, EMAIL, MOT_DE_PASSE, ROLE) VALUES (:username, :email, :password, :role)";

    // Préparation de la requête avec la connexion PDO
    $stmt = $con->prepare($sql);

    // Liaison des valeurs avec les paramètres de la requête préparée
    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':password', $password);
    $stmt->bindParam(':role', $role);

    // Exécution de la requête et vérification du succès
    if ($stmt->execute()) {
        // Message de succès en cas d'insertion réussie
        $alertMessage = $role . ' ajouté avec succès.';
        $alertType = 'success';
    } else {
        // Affichage d'un message d'erreur en cas de problème
        echo "<div style='color: red;'>Erreur: " . $stmt->errorInfo()[2] . "</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gérer les Utilisateurs</title>
    <!-- Inclusion du fichier CSS de Bootstrap -->
    <link rel="stylesheet" href="../boostrap/css/bootstrap.min.css">
    <style>
        /* Styles CSS personnalisés pour améliorer l'interface utilisateur */
        body {
            background-color: #f8f9fa;
        }

        .card-header {
            background-color: #007bff;
            color: white;
        }

        .btn-primary {
            background-color: #007bff;
            border: none;
        }

        .btn-primary:hover {
            background-color: #0056b3;
        }

        .table thead th {
            background-color: #007bff;
            color: white;
        }

        .btn-sm {
            padding: 0.25rem 0.5rem;
        }
    </style>
</head>

<body>
    <div class="container my-5">
        <!-- Bouton de retour à la page admin -->
        <a href="admin.php" class="btn btn-danger btn-lg">Retour</a>
        <h1 class="text-center mb-4">Gérer les Utilisateurs</h1>

        <!-- Formulaire pour ajouter un utilisateur -->
        <div class="card mb-4">
            <div class="card-header">
                Ajouter un Utilisateur
            </div>
            <div class="card-body">
                <!-- Afficher les alertes -->
                <?php if ($alertMessage): ?>
                    <div class="alert alert-<?= htmlspecialchars($alertType) ?>">
                        <?= htmlspecialchars($alertMessage) ?>
                    </div>
                <?php endif; ?>
                <form action="" method="POST">
                    <div class="mb-3">
                        <label for="username" class="form-label">Nom d'utilisateur</label>
                        <input type="text" class="form-control" id="username" name="username" placeholder="Nom d'utilisateur" required>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email" placeholder="exemple@domaine.com" required>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Mot de passe</label>
                        <input type="password" class="form-control" id="password" name="password" placeholder="Mot de passe" required>
                    </div>
                    <div class="mb-3">
                        <label for="role" class="form-label">Rôle</label>
                        <select class="form-select" id="role" name="role" required>
                            <option value="Admin">Administrateur</option>
                            <option value="user">Utilisateur</option>
                            <option value="gest">Gestionnaire</option>
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
                <?php
                // Requête pour récupérer tous les utilisateurs de la base de données
                $result = $con->query("SELECT * FROM utilisateur WHERE ID_UTILISATEUR  != {$_SESSION['user_id']}");

                // Boucle pour afficher chaque utilisateur dans une ligne du tableau
                while ($row = $result->fetch()) {
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($row['ID_UTILISATEUR']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['NOM_UTIL']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['EMAIL']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['ROLE']) . "</td>";
                    echo '<td>
                            <a href="#" class="btn btn-warning btn-sm">Modifier</a>
                            <a href="#" class="btn btn-danger btn-sm">Supprimer</a>
                        </td>';
                    echo "</tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

    <!-- Inclusion du fichier JavaScript de Bootstrap -->
    <script src="../boostrap/js/bootstrap.min.js"></script>
</body>

</html>