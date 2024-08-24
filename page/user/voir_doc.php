<?php
require_once('../conect.php');

// On vérifie si la session de l'utilisateur est active
if (!$_SESSION['user_id']) {
    header('location: ../connexionInscription.php');
}

// Initialisation des variables d'alerte
$alertMessage = null;
$alertType = '';

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
$sql = "SELECT * FROM categories";
$stmt = $con->query($sql);
$categories = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Récupérer les informations du document sélectionné
if (isset($_GET['id'])) {
    $id_doc = $_GET['id'];
    $recup_doc = $con->prepare("SELECT * FROM document WHERE ID_DOCUMENT = ?");
    $recup_doc->execute(array($id_doc));
    $doc = $recup_doc->fetch();
} else {
    $doc = null; // Initialiser $doc si aucun document n'est sélectionné
}

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mes Documents</title>
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
            /* Espacement sous les alertes */
        }
    </style>
</head>
<body>
    <div class="container my-5">
        <a href="user.php" class="btn btn-danger btn-lg">Retour</a>
        <h1 class="text-center mb-4">Mes Documents</h1>
        
        <!-- Formulaire pour ajouter un document -->
        <div class="card mb-4">
            <div class="card-header">
                Modifier un document
            </div>
            <div class="card-body">
                <!-- Afficher les alertes -->
                <?php if ($alertMessage): ?>
                    <div class="alert alert-<?= htmlspecialchars($alertType) ?>">
                        <?= htmlspecialchars($alertMessage) ?>
                    </div>
                <?php endif; ?>

                <?php if ($doc): ?>
                    <form method="POST" enctype="multipart/form-data">
                        <div class="mb-3">
                            <label for="docTitle" class="form-label">Titre du Document</label>
                            <input type="text" class="form-control" value="<?= htmlspecialchars($doc['TITRE']) ?>" id="docTitle" name="docTitle" placeholder="Titre du document" required>
                        </div>
                        <div class="mb-3">
                            <label for="docCategory" class="form-label">Catégorie</label>
                            <select class="form-select" id="docCategory" name="docCategory" required>
                                <?php foreach ($categories as $category): ?>
                                    <option value="<?= htmlspecialchars($category['ID_CATEGORIES']) ?>" <?= $category['ID_CATEGORIES'] == $category['ID_CATEGORIES'] ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($category['NOM']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="docDescription" class="form-label">Description</label>
                            <textarea class="form-control" id="docDescription" name="docDescription" rows="3" placeholder="Description du document" required><?= htmlspecialchars($doc['DESCRIPTION']) ?></textarea>
                        </div>
                        
                        <button type="submit" class="btn btn-primary">Modifier</button>
                    </form>
                <?php else: ?>
                    <div class="alert alert-warning">Aucun document sélectionné pour modification.</div>
                <?php endif; ?>
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
                                <a href="#" class="btn btn-primary btn-sm">Télécharger</a>
                                <a href="visual.php?id=<?= htmlspecialchars($document['ID_DOCUMENT']) ?>" class="btn btn-warning btn-sm">voir</a>
                                <a href="?id=<?= htmlspecialchars($document['ID_DOCUMENT']) ?>" class="btn btn-warning btn-sm">Modifier</a>
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
