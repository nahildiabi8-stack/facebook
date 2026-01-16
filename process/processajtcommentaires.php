<?php
session_start();
require_once "../utils/connexion.php";

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: ../accueil.php");
    exit;
}

if (
    empty($_SESSION['user_id']) ||
    empty($_POST['message_id']) ||
    empty($_POST['lecontenue'])
) {
    die("y'a rien d tout");
}

$utilisateurs_id = (int) $_SESSION['user_id'];
$messages_id     = (int) $_POST['message_id'];
$date = (new DateTime())->format('Y-m-d H:i:s');
$lecontenue      = trim($_POST['lecontenue']);

$stmt = $db->prepare("
    INSERT INTO commentaires (utilisateurs_id, messages_id, contenue, date)
    VALUES (:utilisateurs_id, :messages_id, :contenue, :date)
");

$stmt->execute([
    'utilisateurs_id' => $utilisateurs_id,
    'messages_id'     => $messages_id,
    'contenue'        => $lecontenue,
    'date'        => $date

]);

header("Location: ../lescommentaires.php?message_id=" . $messages_id);
exit;
