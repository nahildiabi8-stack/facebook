<?php
session_start();
if (!isset($_SESSION['pseudo'])) {
    header("Location: ./login.php");
    exit;
}

require_once "./utils/connexion.php";


$stmt = $db->prepare("SELECT id FROM utilisateurs WHERE nomutilisateur = ?");
$stmt->execute([$_SESSION['nomutilisateur']]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);
$userId = $user['id'] ?? null;

$playlistMusiques = [];
if ($userId) {
    $stmt = $db->prepare("
       SELECT m.*, s.utilisateur_id, u.pseudo, u.nomutilisateur FROM messages m
        JOIN sauvegarder s ON m.id = s.image_id
        JOIN utilisateurs u ON m.utilisateur_id = u.id
        WHERE s.utilisateur_id = ?
        ORDER BY s.id ASC
    ");
    $stmt->execute([$userId]);
    $playlistMusiques = $stmt->fetchAll(PDO::FETCH_ASSOC);
}



$stmt = $db->prepare("SELECT profilepicture FROM utilisateurs WHERE id = :id");
$stmt->execute([
    'id' => $_SESSION['user_id']
]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="./src/output.css">
</head>

<body>
    <div class="min-h-screen bg-gray-50">
        <div class="max-w-7xl mx-auto px-6 py-8 flex gap-8">
            <aside class="w-80 bg-white border border-[#E5E5E5] rounded-lg p-6">
                <h1 class="text-4xl font-extrabold bg-linear-to-r from-[#7E39FB] to-[#B32ACC] text-transparent bg-clip-text">Qwerty</h1>
                <nav class="mt-8 flex flex-col gap-4">
                    <a class="flex items-center gap-3 bg-[#050316] text-white rounded-xl px-4 py-3" href="./accueil.php">Accueil</a>
                    <a class="flex items-center gap-3 rounded-xl px-4 py-3 hover:bg-black/5" href="./explorer.php">Explorer</a>
                    <a class="flex items-center gap-3 rounded-xl px-4 py-3 hover:bg-black/5" href="./tendances.php">Tendances</a>
                    <a class="flex items-center gap-3 rounded-xl px-4 py-3 hover:bg-black/5" href="./sauvegarde.php">Sauvegardé</a>
                    <a class="flex items-center gap-3 rounded-xl px-4 py-3 hover:bg-black/5" href="./parametre.php">Paramètres</a>
                </nav>
            </aside>

            <?php foreach ($playlistMusiques as $lesmessages): ?>

                <?php if ($lesmessages['utilisateur_id'] === $_SESSION['user_id']): ?>

                    <article class="bg-white border border-[#E5E5E5] rounded-xl p-6 hover:shadow">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-4">
                                <?php
                                $photo = !empty($user['profilepicture'])
                                    ? $user['profilepicture']
                                    : 'profilepicture/pfppardfault.png';
                                ?>

                                <img
                                    src="<?= htmlspecialchars($photo) ?>"
                                    alt="Photo de profil"
                                    class="w-12 h-12 rounded-full object-cover">
                                <div>
                                    <div class="font-semibold"><?= htmlspecialchars($lesmessages['pseudo']) ?></div>
                                    <div class="text-sm text-gray-500">@<?= htmlspecialchars($lesmessages['nomutilisateur']) ?> ·<?= htmlspecialchars($lesmessages['date']); ?></div>
                                </div>
                            </div>
                            <div onclick="ouvremenutop()" class="text-gray-400 cursor-pointer">•••</div>
                        </div>
                        <p class="mt-4 text-gray-800"> <?= htmlspecialchars($lesmessages['message']); ?></p>

                        <?php if (!empty($lesmessages['fichier'])): ?>
                            <div class="mt-4 rounded-lg overflow-hidden">
                                <img src="<?= htmlspecialchars($lesmessages['fichier']) ?>" class="w-full h-64 object-fit" alt="paysage">
                            </div>


                        <?php endif; ?>
                        <div class="flex gap-24 items-center justify-center pb-2 pt-4">

                            <div class="flex flex-row gap-2 cursor-pointer">
                                <img
                                    class="like-button w-6 h-6 cursor-pointer"
                                    src="./logos/heart.png"
                                    data-post-id="<?= $lesmessages['id'] ?>"
                                    alt="Like">
                                <?= htmlspecialchars($lesmessages['likes']) ?>
                            </div>

                            <div class="flex flex-row gap-2 cursor-pointer">
                                <img src="./logos/beacon.png" alt="Comment" class="w-6 h-6">
                                <p>1</p>
                            </div>

                            <div class="flex flex-row gap-2 cursor-pointer">
                                <img src="./logos/share.png" alt="Share" class="w-6 h-6">
                                <p>1</p>
                            </div>
                            <form action="./process/processajtsauvegarder.php" method="POST">
                                <div class="cursor-pointer">
                                    <input type="hidden" name="image_id" value="<?= $lesmessages['id'] ?>">
                                    <button type="submit">
                                        <img src="./logos/bookmark.png" alt="Favourite" class="w-6 h-6">
                                    </button>

                                </div>
                            </form>


                        </div>

                    </article>
                <?php endif; ?>

                <?php if ($lesmessages['utilisateur_id'] !== $_SESSION['user_id']): ?>
                    <article class="bg-white border border-[#E5E5E5] rounded-xl p-6 hover:shadow">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-4">
                                <img src="./logos/pfppardfault.png" class="w-12 h-12 rounded-full" alt="">
                                <div>
                                    <div class="font-semibold"><?= htmlspecialchars($lesmessages['pseudo']) ?></div>
                                    <div class="text-sm text-gray-500">@<?= htmlspecialchars($lesmessages['nomutilisateur']) ?> ·<?= htmlspecialchars($lesmessages['date']); ?></div>
                                </div>
                            </div>
                            <div onclick="ouvremenutop()" class="text-gray-400 cursor-pointer">•••</div>
                        </div>
                        <p class="mt-4 text-gray-800"> <?= htmlspecialchars($lesmessages['message']); ?></p>
                        <?php if (!empty($lesmessages['fichier'])): ?>
                            <div class="mt-4 rounded-lg overflow-hidden">
                                <img src="<?= htmlspecialchars($lesmessages['fichier']) ?>" class="w-full h-80 object-fit" alt="paysage">
                            </div>

                        <?php endif; ?>
                        <div class="flex gap-16 items-center justify-center pb-2 pt-4">

                            <div class="flex flex-row gap-2 cursor-pointer">
                                <img
                                    class="like-button w-6 h-6 cursor-pointer"
                                    src="./logos/heart.png"
                                    data-post-id="<?= $lesmessages['id'] ?>"
                                    alt="Like">
                                <?= htmlspecialchars($lesmessages['likes']) ?>

                            </div>

                            <div class="flex flex-row gap-2 cursor-pointer">
                                <img src="./logos/beacon.png" alt="Comment" class="w-6 h-6">
                                <p>1</p>
                            </div>

                            <div class="flex flex-row gap-2 cursor-pointer">
                                <img src="./logos/share.png" alt="Share" class="w-6 h-6">
                                <p>1</p>
                            </div>

                            <div class="cursor-pointer">
                                <img src="./logos/bookmark.png" alt="Favourite" class="w-6 h-6">
                            </div>

                        </div>


                    </article>
                <?php endif; ?>

            <?php endforeach; ?>

        </div>
    </div>
    </div>
</body>

</html>