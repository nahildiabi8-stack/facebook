<?php
session_start();
require_once "../utils/connexion.php";

if (!isset($_SESSION['user_id'], $_POST['post_id'])) {
    echo json_encode(['success' => false]);
    exit;
}

$messages_id = (int) $_POST['post_id'];
$utilisateurs_id = (int) $_SESSION['user_id'];

// Vérifier si CE user a déjà liké
$stmt = $db->prepare("
    SELECT 1 FROM likesmsg
    WHERE utilisateurs_id = :user
    AND messages_id = :msg
");
$stmt->execute([
    'user' => $utilisateurs_id,
    'msg' => $messages_id
]);

$alreadyLiked = $stmt->fetchColumn();

if ($alreadyLiked) {
    // Supprimer UNIQUEMENT son like
    $stmt = $db->prepare("
        DELETE FROM likesmsg
        WHERE utilisateurs_id = :user
        AND messages_id = :msg
    ");
    $stmt->execute([
        'user' => $utilisateurs_id,
        'msg' => $messages_id
    ]);

    // Décrémenter seulement si > 0
    $stmt = $db->prepare("
        UPDATE messages
        SET likes = GREATEST(likes - 1, 0)
        WHERE id = :msg
    ");
    $stmt->execute([
        'msg' => $messages_id
    ]);

    $action = 'retirer';
} else {
    // Ajouter son like
    $stmt = $db->prepare("
        INSERT INTO likesmsg (utilisateurs_id, messages_id)
        VALUES (:user, :msg)
    ");
    $stmt->execute([
        'user' => $utilisateurs_id,
        'msg' => $messages_id
    ]);

    $stmt = $db->prepare("
        UPDATE messages
        SET likes = likes + 1
        WHERE id = :msg
    ");
    $stmt->execute([
        'msg' => $messages_id
    ]);

    $action = 'ajouter';
}

echo json_encode([
    'success' => true,
    'action' => $action
]);
