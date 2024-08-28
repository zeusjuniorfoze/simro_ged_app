<?php
 require_once('../conect.php');

// Vérifier si la session est active
if (!isset($_SESSION['user_id'])) {
    header('Location: ../connexionInscription.php');
    exit;
}

// Récupérer la liste des documents
$sql_docs = "SELECT * FROM document";
$stmt_docs = $con->prepare($sql_docs);
$stmt_docs->execute();
$documents = $stmt_docs->fetchAll(PDO::FETCH_ASSOC);

// Récupérer la liste des utilisateurs
$sql_users = "SELECT * FROM utilisateur";
$stmt_users = $con->prepare($sql_users);
$stmt_users->execute();
$users = $stmt_users->fetchAll(PDO::FETCH_ASSOC);

// Récupérer les permissions
$sql_perms = "SELECT * FROM permissions";
$stmt_perms = $con->prepare($sql_perms);
$stmt_perms->execute();
$permissions = $stmt_perms->fetchAll(PDO::FETCH_ASSOC);

// Récupérer les permissions des utilisateurs pour chaque document
$sql_user_perms = "SELECT up.ID_UTILISATEUR, up.ID_DOCUMENT, up.ID_PERMISSIONS, d.TITRE, u.NOM_UTIL, p.CAN_VIEW, p.CAN_EDIT, p.CAN_DELETE
                    FROM user_permission up
                    JOIN document d ON up.ID_DOCUMENT = d.ID_DOCUMENT
                    JOIN utilisateur u ON up.ID_UTILISATEUR = u.ID_UTILISATEUR
                    JOIN permissions p ON up.ID_PERMISSIONS = p.ID_PERMISSIONS";

$stmt_user_perms = $con->prepare($sql_user_perms);
$stmt_user_perms->execute();
$user_permissions = $stmt_user_perms->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gérer les Permissions</title>
    <link rel="stylesheet" href="../boostrap/css/bootstrap.min.css">
</head>
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

    .btn-info,
    .btn-danger {
        margin-right: 5px;
    }
</style>

<body>
    <div class="container my-5">
        <a href="gest.php" class="btn btn-danger btn-lg">Retour</a>
        <h1 class="text-center mb-4">Gérer les Permissions</h1>

        <!-- Tableau pour gérer les permissions des documents -->
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>ID Document</th>
                    <th>Titre du Document</th>
                    <th>Utilisateur</th>
                    <th>Permissions</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($user_permissions as $up): ?>
                    <tr>
                        <td><?= htmlspecialchars($up['ID_DOCUMENT']) ?></td>
                        <td><?= htmlspecialchars($up['TITRE']) ?></td>
                        <td><?= htmlspecialchars($up['NOM_UTIL']) ?></td>
                        <td>
                            <select name="permission" class="form-select form-select-sm" data-doc="<?= htmlspecialchars($up['ID_DOCUMENT']) ?>" data-user="<?= htmlspecialchars($up['ID_UTILISATEUR']) ?>">
                                <option value="none" <?= $up['CAN_VIEW'] === 'none' ? 'selected' : '' ?>>Aucune</option>
                                <option value="view" <?= $up['CAN_VIEW'] === 'view' ? 'selected' : '' ?>>Lecture</option>
                                <option value="edit" <?= $up['CAN_EDIT'] === 'edit' ? 'selected' : '' ?>>Écriture</option>
                                <option value="all" <?= $up['CAN_DELETE'] === 'all' ? 'selected' : '' ?>>Lecture et Écriture</option>
                            </select>
                        </td>
                        <td>
                            <a href="#" class="btn btn-primary btn-sm save-permission" data-doc="<?= htmlspecialchars($up['ID_DOCUMENT']) ?>" data-user="<?= htmlspecialchars($up['ID_UTILISATEUR']) ?>">Enregistrer</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <script src="../boostrap/js/bootstrap.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            $('.save-permission').click(function(e) {
                e.preventDefault();

                var button = $(this);
                var docId = button.data('doc');
                var userId = button.data('user');
                var permSelect = $('select[data-doc="' + docId + '"][data-user="' + userId + '"]');
                var permission = permSelect.val();

                $.ajax({
                    url: 'update_permission.php',
                    type: 'POST',
                    data: {
                        doc_id: docId,
                        user_id: userId,
                        permission: permission
                    },
                    success: function(response) {
                        alert('Permissions mises à jour avec succès.');
                    },
                    error: function() {
                        alert('Erreur lors de la mise à jour des permissions.');
                    }
                });
            });
        });
    </script>
</body>

</html>