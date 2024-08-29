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

// Gestion de l'ajout ou de la modification d'une permission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $canView = isset($_POST['canView']) ? 'yes' : 'no';
    $canEdit = isset($_POST['canEdit']) ? 'yes' : 'no';
    $canDelete = isset($_POST['canDelete']) ? 'yes' : 'no';

    if (isset($_POST['permissionId']) && $_POST['permissionId'] != '') {
        // Modifier une permission existante
        $permissionId = $_POST['permissionId'];
        $sql = "UPDATE permissions SET CAN_VIEW = :canView, CAN_EDIT = :canEdit, CAN_DELETE = :canDelete WHERE ID_PERMISSIONS = :id";
        $stmt = $con->prepare($sql);
        $stmt->bindParam(':canView', $canView);
        $stmt->bindParam(':canEdit', $canEdit);
        $stmt->bindParam(':canDelete', $canDelete);
        $stmt->bindParam(':id', $permissionId, PDO::PARAM_INT);
        $stmt->execute();

        $alertMessage = 'Permission modifiée avec succès.';
        $alertType = 'success';
    } else {
        // Ajouter une nouvelle permission
        $sql = "INSERT INTO permissions (CAN_VIEW, CAN_EDIT, CAN_DELETE) VALUES (:canView, :canEdit, :canDelete)";
        $stmt = $con->prepare($sql);
        $stmt->bindParam(':canView', $canView);
        $stmt->bindParam(':canEdit', $canEdit);
        $stmt->bindParam(':canDelete', $canDelete);
        $stmt->execute();

        $alertMessage = 'Permission ajoutée avec succès.';
        $alertType = 'success';
    }
}

// Gestion de la suppression d'une permission
if (isset($_GET['delete'])) {
    $permissionId = $_GET['delete'];
    $sql = "DELETE FROM permissions WHERE ID_PERMISSIONS = :id";
    $stmt = $con->prepare($sql);
    $stmt->bindParam(':id', $permissionId, PDO::PARAM_INT);
    $stmt->execute();

    $alertMessage = 'Permission supprimée avec succès.';
    $alertType = 'success';
}

// Récupérer les permissions pour affichage
$sql = "SELECT * FROM permissions";
$stmt = $con->query($sql);
$permissions = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gérer les Permissions</title>
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
        <a href="admin.php" class="btn btn-danger btn-lg">Retour</a>
        <h1 class="text-center mb-4">Gérer les Permissions</h1>

        <!-- Afficher les alertes -->
        <?php if ($alertMessage): ?>
            <div class="alert alert-<?= htmlspecialchars($alertType) ?>">
                <?= htmlspecialchars($alertMessage) ?>
            </div>
        <?php endif; ?>

        <!-- Formulaire pour ajouter/modifier une permission -->
        <div class="card mb-4">
            <div class="card-header">
                Ajouter/Modifier une Permission
            </div>
            <div class="card-body">
                <form method="POST">
                    <input type="hidden" id="permissionId" name="permissionId">
                    <div class="mb-3 form-check">
                        <input type="checkbox" class="form-check-input" id="canView" name="canView">
                        <label class="form-check-label" for="canView">Voir</label>
                    </div>
                    <div class="mb-3 form-check">
                        <input type="checkbox" class="form-check-input" id="canEdit" name="canEdit">
                        <label class="form-check-label" for="canEdit">Modifier</label>
                    </div>
                    <div class="mb-3 form-check">
                        <input type="checkbox" class="form-check-input" id="canDelete" name="canDelete">
                        <label class="form-check-label" for="canDelete">Supprimer</label>
                    </div>
                    <button type="submit" class="btn btn-primary">Enregistrer</button>
                </form>
            </div>
        </div>

        <!-- Tableau pour afficher les permissions -->
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Voir</th>
                    <th>Modifier</th>
                    <th>Supprimer</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (count($permissions) > 0): ?>
                    <?php foreach ($permissions as $permission): ?>
                        <tr>
                            <td><?= htmlspecialchars($permission['ID_PERMISSIONS']) ?></td>
                            <td><?= htmlspecialchars($permission['CAN_VIEW']) ?></td>
                            <td><?= htmlspecialchars($permission['CAN_EDIT']) ?></td>
                            <td><?= htmlspecialchars($permission['CAN_DELETE']) ?></td>
                            <td>
                                <a href="javascript:void(0);" class="btn btn-warning btn-sm"
                                    onclick="editPermission(<?= htmlspecialchars($permission['ID_PERMISSIONS']) ?>, '<?= htmlspecialchars($permission['CAN_VIEW']) ?>', '<?= htmlspecialchars($permission['CAN_EDIT']) ?>', '<?= htmlspecialchars($permission['CAN_DELETE']) ?>')">Modifier</a>
                                <a href="?delete=<?= htmlspecialchars($permission['ID_PERMISSIONS']) ?>" class="btn btn-danger btn-sm">Supprimer</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5" class="text-center">Aucune permission trouvée</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <script>
        function editPermission(id, canView, canEdit, canDelete) {
            document.getElementById('permissionId').value = id;
            document.getElementById('canView').checked = (canView === 'yes');
            document.getElementById('canEdit').checked = (canEdit === 'yes');
            document.getElementById('canDelete').checked = (canDelete === 'yes');
        }
    </script>

    <script src="../boostrap/js/bootstrap.min.js"></script>
</body>

</html>