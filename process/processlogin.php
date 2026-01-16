<?php
session_start();
require_once "../utils/connexion.php";
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $pseudo = $_POST['pseudo'] ?? '';
    $nomutilisateur = $_POST['nomutilisateur'] ?? '';
    $password = $_POST['password'] ?? '';


    $stmt = $db->prepare("
        SELECT *
 FROM utilisateurs
  WHERE pseudo = :pseudo
    OR nomutilisateur = :nomutilisateur;
    ");

    $stmt->execute([
        'pseudo' => $pseudo,
        'nomutilisateur' => $nomutilisateur

    ]);

    $legars = $stmt->fetch();

    if (password_verify($password, $legars['password'])) {
        $_SESSION['user_id'] = (int) $legars['id'];
        $_SESSION['pseudo'] = $legars['pseudo'];
         $_SESSION['nomutilisateur'] = $legars['nomutilisateur'];


        header("Location: ../accueil.php");
        exit;
    } else {
        header("Location: ../erreurlogin.php");
        exit;
    }
}
