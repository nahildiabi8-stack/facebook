
<?php
session_start();
require_once "../utils/connexion.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $lecontenue = $_POST['lecontenue'] ?? null;
    $date = (new DateTime())->format('Y-m-d H:i:s');
    $utilisateur_id = $_SESSION['user_id'] ?? null;
    $nomutilisateur = $_SESSION['nomutilisateur'] ?? null;

    $filePath = null;
    $erreur = "Le fichier n'a pas pu être envoyé.";

    if (isset($_FILES['uploadimg']) && !empty($_FILES['uploadimg']['name'])) {
        $dossier = "../upload/";
        if (!is_dir($dossier)) {
            mkdir($dossier, 0755, true);
        }

        $nomdufichier = uniqid() . "_" . basename($_FILES['uploadimg']['name']);
        $destination = $dossier . $nomdufichier;

        if (is_uploaded_file($_FILES['uploadimg']['tmp_name']) && move_uploaded_file($_FILES['uploadimg']['tmp_name'], $destination)) {
            $filePath = "upload/" . $nomdufichier;
        }
    }

    if ($filePath !== null) {
        $stmt = $db->prepare(
            "INSERT INTO messages (message, date, utilisateur_id, nomutilisateur, fichier)\n VALUES (:lecontenue, :date, :utilisateur_id, :nomutilisateur, :fichier)"
        );

        $stmt->execute([
            'lecontenue' => $lecontenue,
            'date' => $date,
            'utilisateur_id' => $utilisateur_id,
            'nomutilisateur' => $nomutilisateur,
            'fichier' => $filePath
        ]);
    } else {
        $stmt = $db->prepare(
            "INSERT INTO messages (message, date, utilisateur_id, nomutilisateur)\n VALUES (:lecontenue, :date, :utilisateur_id, :nomutilisateur)"
        );

        $stmt->execute([
            'lecontenue' => $lecontenue,
            'date' => $date,
            'utilisateur_id' => $utilisateur_id,
            'nomutilisateur' => $nomutilisateur
        ]);
    }

    header("Location: ../accueil.php");
    exit;
}

?>