<?php
require_once('../conect.php');

// Vérifier si la session est active
if (!isset($_SESSION['user_id'])) {
    header('Location: ../connexionInscription.php');
    exit;
}

// Récupérer les versions des documents
$sql = "SELECT dv.ID_DOCUMENT_VERSION, d.TITRE, dv.VERSION_NUMBER, u.NOM_UTIL AS MODIFIED_BY, dv.CREATED_AT_D 
        FROM document_version dv
        JOIN document d ON dv.ID_DOCUMENT = d.ID_DOCUMENT
        JOIN utilisateur u ON dv.UPDATED_BY = u.ID_UTILISATEUR
        WHERE d.ID_UTILISATEUR = :id_utilisateur
        ORDER BY dv.CREATED_AT_D DESC";
$stmt = $con->prepare($sql);
$stmt->bindParam(':id_utilisateur', $_SESSION['user_id'], PDO::PARAM_INT);
$stmt->execute();
$versions = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Action pour restaurer une version précédente
if (isset($_GET['restore']) && is_numeric($_GET['restore'])) {
    $id_version = $_GET['restore'];

    // Récupérer les informations de la version à restaurer
    $sql = "SELECT * FROM document_version WHERE ID_DOCUMENT_VERSION = :id_version";
    $stmt = $con->prepare($sql);
    $stmt->bindParam(':id_version', $id_version, PDO::PARAM_INT);
    $stmt->execute();
    $version = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($version) {
        // Restaurer la version en mettant à jour le document principal
        $sql = "UPDATE document SET FILE_PATH = :file_path WHERE ID_DOCUMENT = :id_document";
        $stmt = $con->prepare($sql);
        $stmt->bindParam(':file_path', $version['FILE_PATH_D']);
        $stmt->bindParam(':id_document', $version['ID_DOCUMENT'], PDO::PARAM_INT);
        $stmt->execute();

        // Rediriger après la restauration
        header('Location: suivre_ver.php?restored=1');
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Suivre les Versions</title>
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

        .btn-info,
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
        <h1 class="text-center mb-4">Suivre les Versions des Documents</h1>

        <!-- Alerte de restauration réussie -->
        <?php if (isset($_GET['restored']) && $_GET['restored'] == 1): ?>
            <div class="alert alert-success">
                Version restaurée avec succès.
            </div>
        <?php endif; ?>

        <!-- Alerte de téléchargement réussi -->
        <?php if (isset($_GET['downloaded']) && $_GET['downloaded'] == 1): ?>
            <div class="alert alert-success">
                Document téléchargé avec succès.
            </div>
        <?php endif; ?>

        <!-- Tableau pour afficher les versions des documents -->
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Titre</th>
                    <th>Version</th>
                    <th>Modifié Par</th>
                    <th>Date de Modification</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (count($versions) > 0): ?>
                    <?php foreach ($versions as $version): ?>
                        <tr>
                            <td><?= htmlspecialchars($version['ID_DOCUMENT_VERSION']) ?></td>
                            <td><?= htmlspecialchars($version['TITRE']) ?></td>
                            <td><?= htmlspecialchars($version['VERSION_NUMBER']) ?></td>
                            <td><?= htmlspecialchars($version['MODIFIED_BY']) ?></td>
                            <td><?= htmlspecialchars($version['CREATED_AT_D']) ?></td>
                            <td>
                                <a href="voir_document.php?id=<?= htmlspecialchars($version['ID_DOCUMENT_VERSION']) ?>&& downloaded=1" class="btn btn-info btn-sm">Télécharger</a>
                                <a href="suivre_ver.php?restore=<?= htmlspecialchars($version['ID_DOCUMENT_VERSION']) ?>" class="btn btn-danger btn-sm">Restaurer</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6" class="text-center">Aucune version trouvée</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <script src="../boostrap/js/bootstrap.min.js"></script>
</body>

</html>