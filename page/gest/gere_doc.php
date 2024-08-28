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

// Ajout ou modification de documents
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_FILES['docFile']) && !empty($_FILES['docFile']['name'])) {
        $titre = $_POST['docTitle'];
        $description = $_POST['docDescription'];
        $categorieId = $_POST['docCategory'];
        $filePath = '../uploads/' . basename($_FILES['docFile']['name']); // Chemin du fichier

        // Déplacement du fichier uploadé vers le répertoire cible
        if (move_uploaded_file($_FILES['docFile']['tmp_name'], $filePath)) {
            try {
                $con->beginTransaction();

                if (isset($_POST['docId']) && !empty($_POST['docId'])) { // Modification
                    $documentId = $_POST['docId'];
                    $sql = "UPDATE document SET TITRE = :titre, DESCRIPTION = :description, FILE_PATH = :file_path WHERE ID_DOCUMENT = :id_document";
                    $stmt = $con->prepare($sql);
                    $stmt->bindParam(':id_document', $documentId, PDO::PARAM_INT);
                    $stmt->bindParam(':titre', $titre);
                    $stmt->bindParam(':description', $description);
                    $stmt->bindParam(':file_path', $filePath);
                    $stmt->execute();

                    // Mettre à jour la version existante
                    $sql = "UPDATE document_version SET FILE_PATH_D = :file_path, UPDATED_BY = :updated_by WHERE ID_DOCUMENT = :id_document AND VERSION_NUMBER = 'v1'";
                    $stmt = $con->prepare($sql);
                    $stmt->bindParam(':file_path', $filePath);
                    $stmt->bindParam(':updated_by', $_SESSION['user_id'], PDO::PARAM_INT);
                    $stmt->bindParam(':id_document', $documentId, PDO::PARAM_INT);
                    $stmt->execute();

                    $alertMessage = 'Document modifié avec succès.';
                } else {
                    // Ajout
                    $sql = "INSERT INTO document (ID_UTILISATEUR, TITRE, DESCRIPTION, FILE_PATH, CREATED_AT) VALUES (:id_utilisateur, :titre, :description, :file_path, NOW())";
                    $stmt = $con->prepare($sql);
                    $stmt->bindParam(':id_utilisateur', $_SESSION['user_id'], PDO::PARAM_INT);
                    $stmt->bindParam(':titre', $titre);
                    $stmt->bindParam(':description', $description);
                    $stmt->bindParam(':file_path', $filePath);
                    $stmt->execute();

                    $documentId = $con->lastInsertId();

                    // Ajouter la version initiale du document
                    $sql = "INSERT INTO document_version (ID_DOCUMENT, VERSION_NUMBER, FILE_PATH_D, CREATED_AT_D, UPDATED_BY) 
                            VALUES (:id_document, 'v1', :file_path, NOW(), :updated_by)";
                    $stmt = $con->prepare($sql);
                    $stmt->bindParam(':id_document', $documentId, PDO::PARAM_INT);
                    $stmt->bindParam(':file_path', $filePath);
                    $stmt->bindParam(':updated_by', $_SESSION['user_id'], PDO::PARAM_INT);
                    $stmt->execute();
                    // Associer le document à la catégorie choisie
                    $sql = "INSERT INTO contenir (ID_DOCUMENT, ID_CATEGORIES) VALUES (:id_document, :id_categories)";
                    $stmt = $con->prepare($sql);
                    $stmt->bindParam(':id_document', $documentId, PDO::PARAM_INT);
                    $stmt->bindParam(':id_categories', $categorieId, PDO::PARAM_INT);
                    $stmt->execute();
                    $alertMessage = 'Document ajouté avec succès.';
                }

                $con->commit();
                $alertType = 'success';
            } catch (PDOException $e) {
                $con->rollBack();
                $alertMessage = 'Erreur : ' . $e->getMessage();
                $alertType = 'danger';
            }
        } else {
            $alertMessage = 'Échec de l\'upload du fichier.';
            $alertType = 'warning';
        }
    } else {
        $alertMessage = 'Veuillez sélectionner un fichier.';
        $alertType = 'warning';
    }
}

// Suppression de documents
if (isset($_GET['id_suprim'])) {
    $documentId = $_GET['id_suprim'];
    try {
        // Récupérer le chemin du fichier
        $sql = "SELECT FILE_PATH FROM document WHERE ID_DOCUMENT = :id_document";
        $stmt = $con->prepare($sql);
        $stmt->bindParam(':id_document', $documentId, PDO::PARAM_INT);
        $stmt->execute();
        $document = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($document) {
            $filePath = $document['FILE_PATH'];
            // Supprimer le fichier du dossier uploads
            if (file_exists($filePath)) {
                unlink($filePath);
            }

            // Supprimer le document de la base de données
            $sql = "DELETE FROM document WHERE ID_DOCUMENT = :id_document";
            $stmt = $con->prepare($sql);
            $stmt->bindParam(':id_document', $documentId, PDO::PARAM_INT);
            $stmt->execute();

            $alertMessage = 'Document supprimé avec succès.';
            $alertType = 'success';
        } else {
            $alertMessage = 'Document non trouvé.';
            $alertType = 'warning';
        }
    } catch (PDOException $e) {
        $alertMessage = 'Erreur lors de la suppression : ' . $e->getMessage();
        $alertType = 'danger';
    }
}
// Récupérer les documents pour affichage
$sql = "SELECT d.ID_DOCUMENT, d.TITRE, d.DESCRIPTION, cat.NOM AS CATEGORIE, d.CREATED_AT
        FROM document d
        JOIN contenir c ON d.ID_DOCUMENT = c.ID_DOCUMENT
        JOIN categories cat ON c.ID_CATEGORIES = cat.ID_CATEGORIES
        WHERE d.ID_UTILISATEUR = :id_utilisateur";
$stmt = $con->prepare($sql);
$stmt->bindParam(':id_utilisateur', $_SESSION['user_id'], PDO::PARAM_INT);
$stmt->execute();
$documents = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Récupérer les catégories pour le formulaire
$sql = "SELECT ID_CATEGORIES, NOM FROM categories";
$stmt = $con->query($sql);
$categories = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gérer les Documents</title>
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
        <h1 class="text-center mb-4">Gérer les Documents</h1>

        <!-- Formulaire pour ajouter/modifier un document -->
        <div class="card mb-4">
            <div class="card-header">
                Ajouter/Modifier un Document
            </div>
            <div class="card-body">
                <?php if ($alertMessage): ?>
                    <div class="alert alert-<?= htmlspecialchars($alertType) ?>">
                        <?= htmlspecialchars($alertMessage) ?>
                    </div>
                <?php endif; ?>
                <form method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="docId" value="<?= isset($_GET['id_mofi']) ? htmlspecialchars($_GET['id_mofi']) : '' ?>">
                    <div class="mb-3">
                        <label for="docTitle" class="form-label">Titre du Document</label>
                        <input type="text" class="form-control" id="docTitle" name="docTitle" placeholder="Titre du document">
                    </div>
                    <div class="mb-3">
                        <label for="docCategory" class="form-label">Catégorie</label>
                        <select class="form-select" id="docCategory" name="docCategory">
                            <?php foreach ($categories as $category): ?>
                                <option value="<?= htmlspecialchars($category['ID_CATEGORIES']) ?>">
                                    <?= htmlspecialchars($category['NOM']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="docDescription" class="form-label">Description</label>
                        <textarea class="form-control" id="docDescription" name="docDescription" rows="3" placeholder="Description du document"></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="docFile" class="form-label">Fichier</label>
                        <input type="file" class="form-control" id="docFile" name="docFile">
                    </div>
                    <button type="submit" class="btn btn-primary">Enregistrer</button>
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
                    <th>Description</th>
                    <th>Date d'Ajout</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (count($documents) > 0): ?>
                    <?php foreach ($documents as $document): ?>
                        <tr>
                            <td><?= htmlspecialchars($document['ID_DOCUMENT']) ?></td>
                            <td><?= htmlspecialchars($document['TITRE']) ?></td>
                            <td><?= htmlspecialchars($document['CATEGORIE']) ?></td>
                            <td><?= htmlspecialchars($document['DESCRIPTION']) ?></td>
                            <td><?= htmlspecialchars($document['CREATED_AT']) ?></td>
                            <td>
                                <a href="?id_mofi=<?= htmlspecialchars($document['ID_DOCUMENT']) ?>" class="btn btn-warning btn-sm">Modifier</a>
                                <a href="?id_suprim=<?= htmlspecialchars($document['ID_DOCUMENT']) ?>" class="btn btn-danger btn-sm">Supprimer</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6" class="text-center">Aucun document trouvé</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <script src="../boostrap/js/bootstrap.min.js"></script>
</body>

</html>