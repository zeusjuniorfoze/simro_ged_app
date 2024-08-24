<?php
require_once('../conect.php'); // Connexion à la base de données

// On vérifie si la session de l'utilisateur est active ou non
if (!$_SESSION['user_id']) {
    header('location: ../connexionInscription.php');
}

// Récupération des catégories depuis la base de données
try {
    $stmt = $con->query("SELECT ID_CATEGORIES, NOM FROM categories");
    $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Erreur : " . $e->getMessage();
    die();
}

// Initialisation des variables pour les critères de recherche
$searchTitle = '';
$searchCategory = '';
$searchDate = '';

// Vérification si le formulaire de recherche a été soumis
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (isset($_GET['searchTitle'])) {
        $searchTitle = trim($_GET['searchTitle']);
    }
    if (isset($_GET['searchCategory'])) {
        $searchCategory = trim($_GET['searchCategory']);
    }
    if (isset($_GET['searchDate'])) {
        $searchDate = trim($_GET['searchDate']);
    }

    // Construction de la requête SQL dynamique
    $query = "SELECT d.ID_DOCUMENT, d.TITRE, c.NOM AS CATEGORIE, d.CREATED_AT FROM document d
              LEFT JOIN contenir con ON d.ID_DOCUMENT = con.ID_DOCUMENT
              LEFT JOIN categories c ON con.ID_CATEGORIES = c.ID_CATEGORIES
              WHERE 1=1"; // Clause toujours vraie pour faciliter l'ajout dynamique des conditions

    $params = [];

    if ($searchTitle !== '') {
        $query .= " AND d.TITRE LIKE :searchTitle";
        $params[':searchTitle'] = '%' . $searchTitle . '%';
    }

    if ($searchCategory !== '') {
        $query .= " AND c.NOM = :searchCategory";
        $params[':searchCategory'] = $searchCategory;
    }

    if ($searchDate !== '') {
        $query .= " AND DATE(d.CREATED_AT) = :searchDate";
        $params[':searchDate'] = $searchDate;
    }

    $stmt = $con->prepare($query);
    $stmt->execute($params);
    $documents = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rechercher des Documents</title>
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
        <h1 class="text-center mb-4">Rechercher des Documents</h1>

        <!-- Formulaire de recherche -->
        <form method="GET" class="mb-4">
            <div class="row g-3">
                <div class="col-md-4">
                    <label for="searchTitle" class="form-label">Titre</label>
                    <input type="text" name="searchTitle" class="form-control" id="searchTitle" placeholder="Titre du document" value="<?= htmlspecialchars($searchTitle) ?>">
                </div>
                <div class="col-md-4">
                    <label for="searchCategory" class="form-label">Catégorie</label>
                    <select name="searchCategory" class="form-select" id="searchCategory">
                        <option value="">Toutes</option>
                        <?php foreach ($categories as $category): ?>
                            <option value="<?= htmlspecialchars($category['NOM']) ?>" <?= $searchCategory === $category['NOM'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($category['NOM']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-4">
                    <label for="searchDate" class="form-label">Date d'Ajout</label>
                    <input type="date" name="searchDate" class="form-control" id="searchDate" value="<?= htmlspecialchars($searchDate) ?>">
                </div>
            </div>
            <div class="mt-4 text-center">
                <button type="submit" class="btn btn-primary">Rechercher</button>
            </div>
        </form>

        <!-- Résultats de la recherche -->
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Titre</th>
                    <th>Catégorie</th>
                    <th>Date d'Ajout</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($documents)): ?>
                    <?php foreach ($documents as $document): ?>
                        <tr>
                            <td><?= htmlspecialchars($document['ID_DOCUMENT']) ?></td>
                            <td><?= htmlspecialchars($document['TITRE']) ?></td>
                            <td><?= htmlspecialchars($document['CATEGORIE']) ?></td>
                            <td><?= htmlspecialchars($document['CREATED_AT']) ?></td>
                            <td>
                                <a href="#" class="btn btn-primary btn-sm">Télécharger</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5" class="text-center">Aucun document trouvé.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <script src="../boostrap/js/bootstrap.min.js"></script>
</body>

</html>