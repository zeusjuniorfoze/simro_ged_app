<?php
require_once('../conect.php');

// Vérifiez si la session est active
if (!isset($_SESSION['user_id'])) {
    header('Location: ../connexionInscription.php');
    exit;
}

// Initialiser les messages d'alerte
$alertMessage = '';
$alertType = '';

// Gestion de l'ajout ou de la modification d'une catégorie
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = $_POST['catName'];
    $description = $_POST['catDescription'];

    if (isset($_POST['catId']) && $_POST['catId'] != '') {
        // Modifier une catégorie existante
        $catId = $_POST['catId'];
        $sql = "UPDATE categories SET NOM = :nom, DESCRIPTION_C = :description WHERE ID_CATEGORIES = :id";
        $stmt = $con->prepare($sql);
        $stmt->bindParam(':nom', $nom);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':id', $catId, PDO::PARAM_INT);
        $stmt->execute();

        $alertMessage = 'Catégorie modifiée avec succès.';
        $alertType = 'success';
    } else {
        // Ajouter une nouvelle catégorie
        $sql = "INSERT INTO categories (NOM, DESCRIPTION_C) VALUES (:nom, :description)";
        $stmt = $con->prepare($sql);
        $stmt->bindParam(':nom', $nom);
        $stmt->bindParam(':description', $description);
        $stmt->execute();

        $alertMessage = 'Catégorie ajoutée avec succès.';
        $alertType = 'success';
    }
}

// Gestion de la suppression d'une catégorie
if (isset($_GET['delete'])) {
    $catId = $_GET['delete'];
    $sql = "DELETE FROM categories WHERE ID_CATEGORIES = :id";
    $stmt = $con->prepare($sql);
    $stmt->bindParam(':id', $catId, PDO::PARAM_INT);
    $stmt->execute();

    $alertMessage = 'Catégorie supprimée avec succès.';
    $alertType = 'success';
}

// Récupérer les catégories pour affichage
$sql = "SELECT * FROM categories";
$stmt = $con->query($sql);
$categories = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gérer les Catégories</title>
    <link rel="stylesheet" href="../boostrap/css/bootstrap.min.css">
    <style>
        body {
            padding-top: 20px;
        }

        .card-header {
            background-color: #007bff;
            color: white;
        }

        .table thead th {
            background-color: #007bff;
            color: white;
        }

        .btn-warning,
        .btn-danger {
            margin-right: 5px;
        }

        .alert {
            margin-bottom: 20px;
        }
    </style>
</head>

<body>
    <div class="container my-5">
        <a href="gest.php" class="btn btn-danger btn-lg">Retour</a>
        <h1 class="text-center mb-4">Gérer les Catégories</h1>

        <!-- Afficher les alertes -->
        <?php if ($alertMessage): ?>
            <div class="alert alert-<?= htmlspecialchars($alertType) ?>">
                <?= htmlspecialchars($alertMessage) ?>
            </div>
        <?php endif; ?>

        <!-- Formulaire pour ajouter/modifier une catégorie -->
        <div class="card mb-4">
            <div class="card-header">
                Ajouter/Modifier une Catégorie
            </div>
            <div class="card-body">
                <form method="POST">
                    <input type="hidden" id="catId" name="catId">
                    <div class="mb-3">
                        <label for="catName" class="form-label">Nom de la Catégorie</label>
                        <input type="text" class="form-control" id="catName" name="catName" required>
                    </div>
                    <div class="mb-3">
                        <label for="catDescription" class="form-label">Description</label>
                        <textarea class="form-control" id="catDescription" name="catDescription" rows="3"></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">Enregistrer</button>
                </form>
            </div>
        </div>

        <!-- Tableau pour afficher les catégories -->
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nom</th>
                    <th>Description</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (count($categories) > 0): ?>
                    <?php foreach ($categories as $category): ?>
                        <tr>
                            <td><?= htmlspecialchars($category['ID_CATEGORIES']) ?></td>
                            <td><?= htmlspecialchars($category['NOM']) ?></td>
                            <td><?= htmlspecialchars($category['DESCRIPTION_C']) ?></td>
                            <td>
                                <a href="javascript:void(0);" class="btn btn-warning btn-sm"
                                    onclick="editCategory(<?= htmlspecialchars($category['ID_CATEGORIES']) ?>, '<?= htmlspecialchars($category['NOM']) ?>', '<?= htmlspecialchars($category['DESCRIPTION_C']) ?>')">Modifier</a>
                                <a href="?delete=<?= htmlspecialchars($category['ID_CATEGORIES']) ?>" class="btn btn-danger btn-sm">Supprimer</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="4" class="text-center">Aucune catégorie trouvée</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <script>
        function editCategory(id, nom, description) {
            document.getElementById('catId').value = id;
            document.getElementById('catName').value = nom;
            document.getElementById('catDescription').value = description;
        }
    </script>

    <script src="../boostrap/js/bootstrap.min.js"></script>
</body>

</html>