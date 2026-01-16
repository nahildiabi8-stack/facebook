<?php
require_once "../utils/connexion.php";
session_start();

$utilisateur_id = $_SESSION['user_id'] ?? null;
$image_id = $_POST['image_id'] ?? null;

if ($utilisateur_id === null) {
    die("y'a pas d'utilisateur");
}

$likes = $stmt->fetch(PDO::FETCH_ASSOC);

error_log(print_r($likes, true));

if ($likes) {
    $stmt = $db->prepare("
     DELETE  FROM sauvegarder
    WHERE image_id = :image_id
    AND utilisateur_id = :utilisateur_id
    ");

    $stmt->execute([
        'image_id' => $image_id,
        'utilisateur_id' => $utilisateur_id
    ]);

    $action = "retirer";
} else {
    $stmt = $db->prepare("
       INSERT INTO sauvegarder (utilisateur_id, image_id)
        VALUES (:utilisateur_id, :image_id)
    ");

    $stmt->execute([
        ':utilisateur_id' => $utilisateur_id,
        ':image_id' => $image_id,
    ]);

    $action = "ajouter";
}



echo json_encode([
    'success' => true,
    'post_id' => $messages_id,
    'action' => $action
]);
