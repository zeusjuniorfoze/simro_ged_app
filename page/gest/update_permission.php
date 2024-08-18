<?php
require_once('../conect.php');

// Vérifiez que l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    header('Location: ../connexionInscription.php');
    exit;
}

// Vérifiez que les données sont bien reçues
if (isset($_POST['doc_id']) && isset($_POST['user_id']) && isset($_POST['permission'])) {
    $docId = $_POST['doc_id'];
    $userId = $_POST['user_id'];
    $permission = $_POST['permission'];

    // Déterminez les valeurs des permissions
    $canView = $permission === 'view' || $permission === 'all' ? 'view' : 'none';
    $canEdit = $permission === 'edit' || $permission === 'all' ? 'edit' : 'none';
    $canDelete = $permission === 'all' ? 'all' : 'none';

    // Mettez à jour les permissions
    $sql = "REPLACE INTO user_permission (ID_UTILISATEUR, ID_DOCUMENT, ID_PERMISSIONS)
            VALUES (:user_id, :doc_id, (SELECT ID_PERMISSIONS FROM permissions WHERE CAN_VIEW = :can_view AND CAN_EDIT = :can_edit AND CAN_DELETE = :can_delete))";
    $stmt = $con->prepare($sql);
    $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
    $stmt->bindParam(':doc_id', $docId, PDO::PARAM_INT);
    $stmt->bindParam(':can_view', $canView);
    $stmt->bindParam(':can_edit', $canEdit);
    $stmt->bindParam(':can_delete', $canDelete);
    $stmt->execute();

    echo "Permissions mises à jour.";
} else {
    echo "Paramètres manquants.";
}
?>
