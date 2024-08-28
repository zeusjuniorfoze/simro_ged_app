<?php
 require_once('../conect.php');

// Vérification si l'ID est passé en paramètre
if (!isset($_GET['id'])) {
    die('ID du document manquant.');
}

// Récupération de l'ID du document
$id_document = $_GET['id'];

// Requête pour récupérer le document
$sql = "SELECT * FROM document WHERE ID_DOCUMENT = :id_document";
$stmt = $con->prepare($sql);
$stmt->bindParam(':id_document', $id_document, PDO::PARAM_INT);
$stmt->execute();
$document = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$document) {
    die('Document non trouvé.');
}

// Déterminer l'extension du fichier pour choisir le mode de prévisualisation
$file_path = $document['FILE_PATH']; // Assurez-vous que le chemin complet au fichier est stocké dans la colonne CHEMIN
$file_extension = strtolower(pathinfo($file_path, PATHINFO_EXTENSION));

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Prévisualisation du Document</title>
    <link rel="stylesheet" href="../boostrap/css/bootstrap.min.css">
</head>
<body>
    <div class="container my-5">
        <h1 class="text-center mb-4">Prévisualisation du Document</h1>
        <div class="text-center">
            <?php if (in_array($file_extension, ['pdf'])): ?>
                <!-- Affichage PDF -->
                <iframe src="https://drive.google.com/viewerng/viewer?embedded=true&url=<?= urlencode($file_path) ?>" width="100%" height="600px" frameborder="0"></iframe>
            <?php elseif (in_array($file_extension, ['doc', 'docx', 'ppt', 'pptx', 'xls', 'xlsx'])): ?>
                <!-- Affichage pour les documents Microsoft Office -->
                <iframe src="https://view.officeapps.live.com/op/embed.aspx?src=<?= urlencode($file_path) ?>" width="100%" height="600px" frameborder="0"></iframe>
            <?php else: ?>
                <p>Ce type de fichier ne peut pas être prévisualisé.</p>
                <a href="<?= htmlspecialchars($file_path) ?>" class="btn btn-primary">Télécharger le document</a>
            <?php endif; ?>
        </div>
    </div>
    <script src="../boostrap/js/bootstrap.min.js"></script>
</body>
</html>
