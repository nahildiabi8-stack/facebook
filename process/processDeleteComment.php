<?php
session_start();



require_once "../utils/connexion.php";

$comment_id = isset($_POST['comment_id']) ? (int)$_POST['comment_id'] : 0;

$stmt = $db->prepare("SELECT utilisateurs_id FROM commentaires WHERE id = :id");
$stmt->execute(['id' => $comment_id]);
$comment = $stmt->fetch(PDO::FETCH_ASSOC);



$stmt = $db->prepare("DELETE FROM commentaires WHERE id = :id");
$result = $stmt->execute(['id' => $comment_id]);

if ($result) {
    echo json_encode(['success' => true, 'message' => 'bien enlever']);
} else {
    echo json_encode(['success' => false, 'error' => 'peut pas']);
}
?>
