<?php
require_once('../conect.php');

// Vérification si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    header('Location: ../connexionInscription.php');
    exit();
}

// Configuration de la pagination
$logsParPage = 10; // Nombre de logs par page
$pageCourante = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$debut = ($pageCourante - 1) * $logsParPage;

// Récupération des logs avec la limite pour la pagination
$sql = "SELECT A.ID_AUDIT_LOGS, U.NOM_UTIL, A.ACTION, A.TIMESTAMP 
        FROM audit_logs A 
        INNER JOIN utilisateur U ON A.ID_UTILISATEUR = U.ID_UTILISATEUR 
        ORDER BY A.TIMESTAMP DESC 
        LIMIT :debut, :logsParPage";
$stmt = $con->prepare($sql);
$stmt->bindParam(':debut', $debut, PDO::PARAM_INT);
$stmt->bindParam(':logsParPage', $logsParPage, PDO::PARAM_INT);
$stmt->execute();
$logs = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Récupération du nombre total de logs pour la pagination
$sqlTotal = "SELECT COUNT(*) FROM audit_logs";
$totalLogs = $con->query($sqlTotal)->fetchColumn();
$totalPages = ceil($totalLogs / $logsParPage);
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Voir les Logs</title>
    <link rel="stylesheet" href="../boostrap/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f8f9fa;
        }

        .btn-danger {
            background-color: #dc3545;
            border: none;
        }

        .btn-danger:hover {
            background-color: #c82333;
        }

        .table thead th {
            background-color: #007bff;
            color: white;
        }

        .pagination {
            margin-top: 20px;
        }

        .page-link {
            color: #007bff;
        }

        .page-link:hover {
            color: #0056b3;
        }

        .page-item.active .page-link {
            background-color: #007bff;
            border-color: #007bff;
            color: white;
        }
    </style>
</head>

<body>
    <div class="container my-5">
        <a href="admin.php" class="btn btn-danger btn-lg">Retour</a>
        <h1 class="text-center mb-4">Journaux d'Audit</h1>

        <!-- Table pour afficher les logs -->
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Utilisateur</th>
                    <th>Action</th>
                    <th>Date et Heure</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($logs as $log): ?>
                    <tr>
                        <td><?= htmlspecialchars($log['ID_AUDIT_LOGS']); ?></td>
                        <td><?= htmlspecialchars($log['NOM_UTIL']); ?></td>
                        <td><?= htmlspecialchars($log['ACTION']); ?></td>
                        <td><?= htmlspecialchars($log['TIMESTAMP']); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <!-- Pagination -->
        <nav aria-label="Page navigation">
            <ul class="pagination justify-content-center">
                <li class="page-item <?= $pageCourante <= 1 ? 'disabled' : ''; ?>">
                    <a class="page-link" href="?page=<?= $pageCourante - 1; ?>">Précédent</a>
                </li>
                <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                    <li class="page-item <?= $i == $pageCourante ? 'active' : ''; ?>">
                        <a class="page-link" href="?page=<?= $i; ?>"><?= $i; ?></a>
                    </li>
                <?php endfor; ?>
                <li class="page-item <?= $pageCourante >= $totalPages ? 'disabled' : ''; ?>">
                    <a class="page-link" href="?page=<?= $pageCourante + 1; ?>">Suivant</a>
                </li>
            </ul>
        </nav>
    </div>

    <script src="../boostrap/js/bootstrap.min.js"></script>
</body>

</html>