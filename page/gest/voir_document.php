<?php
require_once('../conect.php');

// Vérifiez si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    header('Location: ../connexionInscription.php');
    exit;
}

// Vérifiez si un ID de version de document est passé dans l'URL
if (isset($_GET['id'])) {
    $versionId = $_GET['id'];
    $userId = $_SESSION['user_id']; // L'ID de l'utilisateur connecté

    // Récupérer les informations de la version du document
    $sql = "SELECT dv.FILE_PATH_D, d.TITRE 
            FROM document_version dv 
            JOIN document d ON dv.ID_DOCUMENT = d.ID_DOCUMENT 
            WHERE dv.ID_DOCUMENT_VERSION = :id_version";
    $stmt = $con->prepare($sql);
    $stmt->bindParam(':id_version', $versionId, PDO::PARAM_INT);
    $stmt->execute();
    $documentVersion = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($documentVersion) {
        $filePath = $documentVersion['FILE_PATH_D'];
        $fileName = $documentVersion['TITRE'] . '.' . pathinfo($filePath, PATHINFO_EXTENSION);

        if (file_exists($filePath)) {
            // Insérer l'action de téléchargement dans la table audit_logs
            $sqlAudit = "INSERT INTO audit_logs (ID_UTILISATEUR, ACTION, TIMESTAMP) VALUES (:user_id, :action, NOW())";
            $stmtAudit = $con->prepare($sqlAudit);
            $action = "Téléchargement du document ID " . $versionId; // Description de l'action
            $stmtAudit->bindParam(':user_id', $userId, PDO::PARAM_INT);
            $stmtAudit->bindParam(':action', $action, PDO::PARAM_STR);
            $stmtAudit->execute();

            // Forcer le téléchargement du fichier
            header('Content-Description: File Transfer');
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename="' . basename($filePath) . '"');
            header('Expires: 0');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            header('Content-Length: ' . filesize($filePath));
            readfile($filePath);
            exit;
        } else {
            $_SESSION['erreur'] = "Ce Document N'existe Plus !";
            $alertType = 'warning';
        }
    } else {
        echo "Document non trouvé.";
    }
} else {
    echo "Aucun document spécifié.";
}
?>
