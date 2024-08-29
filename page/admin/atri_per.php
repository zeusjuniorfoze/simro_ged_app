<?php
// Inclusion du fichier de connexion à la base de données
require_once('../conect.php');

// Vérification si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    header('Location: ../connexionInscription.php');
    exit();
}

// Initialiser les messages d'alerte
$alertMessage = '';
$alertType = '';

// Récupérer les documents, utilisateurs et permissions
$sql = "SELECT * FROM document";
$documents = $con->query($sql)->fetchAll(PDO::FETCH_ASSOC);

$sql = "SELECT * FROM utilisateur";
$users = $con->query($sql)->fetchAll(PDO::FETCH_ASSOC);

$sql = "SELECT * FROM permissions";
$permissions = $con->query($sql)->fetchAll(PDO::FETCH_ASSOC);

// Gestion de l'enregistrement des permissions des utilisateurs
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $documentId = $_POST['document_id'];
    $userPermissions = $_POST['user_permissions']; // Array containing user_id and permission_id

    try {
        // Supprimer les anciennes permissions pour ce document
        $sql = "DELETE FROM user_permission WHERE ID_DOCUMENT = :document_id";
        $stmt = $con->prepare($sql);
        $stmt->bindParam(':document_id', $documentId, PDO::PARAM_INT);
        $stmt->execute();

        // Insérer les nouvelles permissions
        foreach ($userPermissions as $userId => $permissionId) {
            $sql = "INSERT INTO user_permission (ID_DOCUMENT, ID_UTILISATEUR, ID_PERMISSION) VALUES (:document_id, :user_id, :permission_id)";
            $stmt = $con->prepare($sql);
            $stmt->bindParam(':document_id', $documentId, PDO::PARAM_INT);
            $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
            $stmt->bindParam(':permission_id', $permissionId, PDO::PARAM_INT);
            $stmt->execute();
        }

        $alertMessage = 'Permissions mises à jour avec succès.';
        $alertType = 'success';
    } catch (PDOException $e) {
        $alertMessage = 'Erreur lors de la mise à jour : ' . $e->getMessage();
        $alertType = 'danger';
    }
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gérer les Permissions des Utilisateurs</title>
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
        <h1 class="text-center mb-4">Gérer les Permissions des Utilisateurs</h1>

        <?php if ($alertMessage): ?>
            <div class="alert alert-<?= htmlspecialchars($alertType) ?>">
                <?= htmlspecialchars($alertMessage) ?>
            </div>
        <?php endif; ?>

        <form action="" method="POST">
            <div class="mb-3">
                <label for="document_id" class="form-label">Document</label>
                <select class="form-select" id="document_id" name="document_id" required>
                    <?php foreach ($documents as $document): ?>
                        <option value="<?= htmlspecialchars($document['ID_DOCUMENT']) ?>">
                            <?= htmlspecialchars($document['TITRE']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <?php foreach ($documents as $document): ?>
                <h4><?= htmlspecialchars($document['TITRE']) ?></h4>

                <?php foreach ($users as $user): ?>
                    <div class="mb-3">
                        <h5><?= htmlspecialchars($user['NOM_UTIL']) ?></h5>
                        <?php foreach ($permissions as $permission): ?>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="user_permissions[<?= htmlspecialchars($user['ID_UTILISATEUR']) ?>]" value="<?= htmlspecialchars($permission['ID_PERMISSIONS']) ?>">
                                <label class="form-check-label">
                                    <?= htmlspecialchars($permission['CAN_VIEW']) ?> - <?= htmlspecialchars($permission['CAN_EDIT']) ?> - <?= htmlspecialchars($permission['CAN_DELETE']) ?>
                                </label>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endforeach; ?>
            <?php endforeach; ?>

            <button type="submit" class="btn btn-primary">Enregistrer</button>
        </form>
    </div>

    <script src="../boostrap/js/bootstrap.min.js"></script>
</body>

</html>