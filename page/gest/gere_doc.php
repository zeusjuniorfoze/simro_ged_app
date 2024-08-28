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

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['docFile'])) {
    $titre = $_POST['docTitle'];
    $description = $_POST['docDescription'];
    $categorieId = $_POST['docCategory'];
    $filePath = '../uploads/' . basename($_FILES['docFile']['name']); // Chemin du fichier

    // Déplacement du fichier uploadé vers le répertoire cible
    if (move_uploaded_file($_FILES['docFile']['tmp_name'], $filePath)) {
        try {
            // Commencer une transaction
            $con->beginTransaction();
            
            // Insérer le document dans la base de données
            $sql = "INSERT INTO document (ID_UTILISATEUR, TITRE, DESCRIPTION, FILE_PATH, CREATED_AT) VALUES (:id_utilisateur, :titre, :description, :file_path, NOW())";
            $stmt = $con->prepare($sql);
            $stmt->bindParam(':id_utilisateur', $_SESSION['user_id'], PDO::PARAM_INT);
            $stmt->bindParam(':titre', $titre);
            $stmt->bindParam(':description', $description);
            $stmt->bindParam(':file_path', $filePath);
            $stmt->execute();
            
            // Récupérer l'ID du document inséré
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
            
            // Commit de la transaction
            $con->commit();

            $alertMessage = 'Document ajouté avec succès.';
            $alertType = 'success';
        } catch (PDOException $e) {
            // Rollback en cas d'erreur
            $con->rollBack();
            $alertMessage = 'Erreur : ' . $e->getMessage();
            $alertType = 'danger';
        }
    } else {
        $alertMessage = 'Échec de l\'upload du fichier.';
        $alertType = 'warning';
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
        .btn-warning, .btn-danger {
            margin-right: 5px;
        }
        .alert {
            margin-bottom: 20px; /* Espacement sous les alertes */
        }
    </style>
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
                <!-- Afficher les alertes -->
                <?php if ($alertMessage): ?>
                    <div class="alert alert-<?= htmlspecialchars($alertType) ?>">
                        <?= htmlspecialchars($alertMessage) ?>
                    </div>
                <?php endif; ?>
                <?php if(isset($_SERVER['erreur'])){
                    echo $_SESSION['erreur'];
                } ?>

                <form method="POST" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label for="docTitle" class="form-label">Titre du Document</label>
                        <input type="text" class="form-control" id="docTitle" name="docTitle" placeholder="Titre du document"  d>
                    </div>
                    <div class="mb-3">
                        <label for="docCategory" class="form-label">Catégorie</label>
                        <select class="form-select" id="docCategory" name="docCategory"  d>
                            <?php foreach ($categories as $category): ?>
                                <option value="<?= htmlspecialchars($category['ID_CATEGORIES']) ?>">
                                    <?= htmlspecialchars($category['NOM']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="docDescription" class="form-label">Description</label>
                        <textarea class="form-control" id="docDescription" name="docDescription" rows="3" placeholder="Description du document"  d></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="docFile" class="form-label">Fichier</label>
                        <input type="file" class="form-control" id="docFile" name="docFile"  d>
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
                                <a href="modifier_document.php?id=<?= htmlspecialchars($document['ID_DOCUMENT']) ?>" class="btn btn-warning btn-sm">Modifier</a>
                                <a href="supprimer_document.php?id=<?= htmlspecialchars($document['ID_DOCUMENT']) ?>" class="btn btn-danger btn-sm">Supprimer</a>
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
