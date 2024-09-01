<?php
require_once('../conect.php');

// Vérification si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    header('Location: ../connexionInscription.php');
    exit();
}

// Initialiser les messages d'alerte
$alertMessage = '';
$alertType = '';

// Gestion de l'insertion des utilisateurs
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_user'])) {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
    $role = $_POST['role'];

    $sql = "INSERT INTO utilisateur (NOM_UTIL, EMAIL, MOT_DE_PASSE, ROLE) VALUES (:username, :email, :password, :role)";
    $stmt = $con->prepare($sql);
    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':password', $password);
    $stmt->bindParam(':role', $role);

    if ($stmt->execute()) {
        $alertMessage = 'Utilisateur ajouté avec succès.';
        $alertType = 'success';
    } else {
        $alertMessage = 'Erreur: ' . $stmt->errorInfo()[2];
        $alertType = 'danger';
    }
}

// Gestion de la modification d'un utilisateur
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_user'])) {
    $userId = $_POST['id'];
    $username = $_POST['username'];
    $email = $_POST['email'];
    $role = $_POST['role'];

    $sql = "UPDATE utilisateur SET NOM_UTIL = :username, EMAIL = :email, ROLE = :role WHERE ID_UTILISATEUR = :id";
    $stmt = $con->prepare($sql);
    $stmt->bindParam(':id', $userId, PDO::PARAM_INT);
    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':role', $role);

    if ($stmt->execute()) {
        $alertMessage = 'Utilisateur mis à jour avec succès.';
        $alertType = 'success';
    } else {
        $alertMessage = 'Erreur: ' . $stmt->errorInfo()[2];
        $alertType = 'danger';
    }
}

// Gestion de la suppression d'un utilisateur
if (isset($_GET['id_suprim'])) {
    $userId = $_GET['id_suprim'];
    $sql = "DELETE FROM utilisateur WHERE ID_UTILISATEUR = :id";
    $stmt = $con->prepare($sql);
    $stmt->bindParam(':id', $userId, PDO::PARAM_INT);

    if ($stmt->execute()) {
        $alertMessage = 'Utilisateur supprimé avec succès.';
        $alertType = 'success';
    } else {
        $alertMessage = 'Erreur: ' . $stmt->errorInfo()[2];
        $alertType = 'danger';
    }
}
// Gestion de l'affichage des informations d'un utilisateur pour modification
$userToEdit = null;
if (isset($_GET['id_modifier'])) {
    $userId = $_GET['id_modifier'];
    $sql = "SELECT * FROM utilisateur WHERE ID_UTILISATEUR = :id";
    $stmt = $con->prepare($sql);
    $stmt->bindParam(':id', $userId, PDO::PARAM_INT);
    $stmt->execute();
    $userToEdit = $stmt->fetch(PDO::FETCH_ASSOC);
}

// Récupérer tous les utilisateurs pour affichage
$result = $con->query("SELECT * FROM utilisateur WHERE ID_UTILISATEUR  != {$_SESSION['user_id']}");

?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gérer les Utilisateurs</title>
    <link rel="stylesheet" href="../boostrap/css/bootstrap.min.css">
    <style>
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
        <a href="admin.php" class="btn btn-danger btn-lg">Retour</a>
        <h1 class="text-center mb-4">Gérer les Utilisateurs</h1>

        <!-- Afficher les alertes -->
        <?php if ($alertMessage): ?>
            <div class="alert alert-<?= htmlspecialchars($alertType) ?>">
                <?= htmlspecialchars($alertMessage) ?>
            </div>
        <?php endif; ?>

        <!-- Formulaire pour ajouter un utilisateur -->
        <div class="card mb-4">
            <div class="card-header">
                Ajouter un Utilisateur
            </div>
            <div class="card-body">
                <form action="" method="POST">
                    <input type="hidden" name="add_user" value="1">
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
        <!-- Formulaire pour modifier un utilisateur -->
        <?php if ($userToEdit): ?>
            <div class="card mb-4">
                <div class="card-header">
                    Modifier un Utilisateur
                </div>
                <div class="card-body">
                    <form action="" method="POST">
                        <input type="hidden" name="update_user" value="1">
                        <input type="hidden" name="id" value="<?= htmlspecialchars($userToEdit['ID_UTILISATEUR']) ?>">
                        <div class="mb-3">
                            <label for="username" class="form-label">Nom d'utilisateur</label>
                            <input type="text" class="form-control" id="username" name="username" value="<?= htmlspecialchars($userToEdit['NOM_UTIL']) ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email" value="<?= htmlspecialchars($userToEdit['EMAIL']) ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="role" class="form-label">Rôle</label>
                            <select class="form-select" id="role" name="role" required>
                                <option value="Admin" <?= $userToEdit['ROLE'] == 'Admin' ? 'selected' : '' ?>>Administrateur</option>
                                <option value="user" <?= $userToEdit['ROLE'] == 'user' ? 'selected' : '' ?>>Utilisateur</option>
                                <option value="gest" <?= $userToEdit['ROLE'] == 'gest' ? 'selected' : '' ?>>Gestionnaire</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary">Mettre à jour</button>
                    </form>
                </div>
            </div>
        <?php endif; ?>

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
                <?php while ($row = $result->fetch()): ?>
                    <tr>
                        <td><?= htmlspecialchars($row['ID_UTILISATEUR']) ?></td>
                        <td><?= htmlspecialchars($row['NOM_UTIL']) ?></td>
                        <td><?= htmlspecialchars($row['EMAIL']) ?></td>
                        <td><?= htmlspecialchars($row['ROLE']) ?></td>
                        <td>
                            <a href="?id_modifier=<?= htmlspecialchars($row['ID_UTILISATEUR']) ?>" class="btn btn-warning btn-sm">Modifier</a>
                            <a href="?id_suprim=<?= htmlspecialchars($row['ID_UTILISATEUR']) ?>" class="btn btn-danger btn-sm" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet utilisateur ?');">Supprimer</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

    <script src="../boostrap/js/bootstrap.min.js"></script>
</body>

</html>