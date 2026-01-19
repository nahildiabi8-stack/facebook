<?php
session_start();


require_once "../utils/connexion.php";

$message_id = isset($_POST['message_id']) ? (int)$_POST['message_id'] : 0;



$stmt = $db->prepare("SELECT utilisateur_id FROM messages WHERE id = :id");
$stmt->execute(['id' => $message_id]);
$message = $stmt->fetch(PDO::FETCH_ASSOC);


$stmt = $db->prepare("DELETE FROM messages WHERE id = :id");
$result = $stmt->execute(['id' => $message_id]);

if ($result) {
    echo json_encode(['success' => true, 'message' => 'Message deleted successfully']);
} else {
    echo json_encode(['success' => false, 'error' => 'Failed to delete message']);
}
?>
