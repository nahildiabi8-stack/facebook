
<?php

require_once "../utils/connexion.php";
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $pseudo    = $_POST['pseudo'] ?? null;
    $password = $_POST['password'] ?? null;
    $nomutilisateur = $_POST['nomutilisateur'] ?? null;

    $stmt = $db->prepare("
        INSERT INTO utilisateurs (pseudo, password, nomutilisateur)
        VALUES (:pseudo, :password, :nomutilisateur)
    ");

    $stmt->execute([
        'pseudo'  => $pseudo,
        'nomutilisateur'  => $nomutilisateur,
        'password' => password_hash($password, PASSWORD_DEFAULT)
    ]);
    header("Location: ../login.php");
}

?>