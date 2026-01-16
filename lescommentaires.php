<?php
session_start();
if (!isset($_SESSION['pseudo'])) {
    header("Location: ./login.php");
    exit;
}



$message_id = (int) $_GET['message_id'];
require_once "./utils/connexion.php";

$stmt = $db->prepare("
    SELECT messages.*, utilisateurs.pseudo
    FROM messages
    JOIN utilisateurs ON messages.utilisateur_id = utilisateurs.id
    WHERE messages.id = :message_id
");
$stmt->execute([
    'message_id' => $message_id
]);
$message = $stmt->fetch(PDO::FETCH_ASSOC);

$stmt = $db->prepare("
    SELECT commentaires.*, utilisateurs.pseudo
    FROM commentaires
    JOIN utilisateurs ON commentaires.utilisateurs_id = utilisateurs.id
    WHERE commentaires.messages_id = :message_id
    ORDER BY commentaires.date ASC
");
$stmt->execute([
    'message_id' => $message_id
]);

$commentaires = $stmt->fetchAll(PDO::FETCH_ASSOC);


$stmt = $db->prepare("SELECT profilepicture FROM utilisateurs WHERE id = :id");
$stmt->execute([
    'id' => $_SESSION['user_id']
]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);
// var_dump($_SESSION);
// die()

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

            <main class="flex-1 space-y-6">
                <form action="./process/processajtcommentaires.php" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="message_id" value="<?= $message_id ?>">
                    <div class="bg-white border border-[#E5E5E5] rounded-xl p-4">
                        <div class="bg-white border border-[#E5E5E5] rounded-xl p-6">
                            <div class="flex items-start gap-4">
                                <?php
                                $photo = !empty($user['profilepicture'])
                                    ? $user['profilepicture']
                                    : 'profilepicture/pfppardfault.png';
                                ?>

                                <img
                                    src="<?= htmlspecialchars($photo) ?>"
                                    alt="Photo de profil"
                                    class="w-12 h-12 rounded-full object-cover">
                                <div class="flex-1">
                                    <textarea id="lecontenue" name="lecontenue" placeholder="Quoi de neuf ?" class="w-full p-4 bg-gray-100 rounded-xl h-24 resize-none"></textarea>
                                    <div class="flex items-center justify-between mt-3">
                                        <div class="flex gap-3 items-center">

                                            <label class="cursor-pointer text-gray-500 hover:text-gray-700">
                                                <input type="file" class="hidden" name="uploadimg" id="uploadimg">
                                                <img src="./logos/fichier-graphique-ligne.png" class="w-6 h-6" alt="">
                                            </label>
                                            <button class="text-gray-500 cursor-pointer">
                                                <img src="./logos/le-sourire.png" class="w-6 h-6" alt="">
                                            </button>
                                            <button class="text-gray-500 cursor-pointer">
                                                <img src="./logos/micro-cercle.png" class="w-6 h-6" alt="">
                                            </button>
                                        </div>
                                        <button class="bg-[#030213] text-white px-6 py-2 rounded-2xl cursor-pointer">Publier</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>

                <?php foreach ($commentaires as $commentaire): ?>
                    <article class="flex flex-col">
                        <div class="bg-gray-100 rounded-lg p-3 mt-3">
                            <div class="text-sm font-semibold">
                                <?= htmlspecialchars($commentaire['pseudo']) ?>
                            </div>

                            <div class="text-sm font-semibold">
                                <?= htmlspecialchars($commentaire['date']) ?>
                            </div>


                            <p class="text-sm">
                                <?= htmlspecialchars($commentaire['contenue']) ?>
                            </p>
                        </div>
                    </article>

                <?php endforeach; ?>











            </main>
        </div>
    </div>

    <a class="text-black deleteletruc" href="./process/processdeconnectecompte.php">deconnecte toi ici</a>
</body>

<script>
    (function() {
        const fileInput = document.getElementById('uploadimg');
        const fileLabel = document.getElementById('file_label');
        const fileName = document.getElementById('file_name');
        const les3trucs = document.querySelectorAll(".deleteletruc");
        if (!fileInput) return;
        fileInput.addEventListener('change', () => {
            if (fileInput.files && fileInput.files.length) {
                fileLabel.textContent = 'Fichier sélectionné';
                fileName.textContent = fileInput.files[0].name;
            } else {
                fileLabel.textContent = 'Choisir un fichier';
                fileName.textContent = 'Aucun fichier choisi';
            }
        });
    })();
    document.querySelectorAll('.like-button').forEach(button => {
        button.addEventListener('click', () => {
            const postId = button.dataset.postId;
            if (!postId) return;

            fetch('./process/processlikes.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded'
                    },
                    body: 'post_id=' + encodeURIComponent(postId)
                })
                .then(response => response.json())
                .then(data => {
                    if (!data.success) return;

                    const img = button.querySelector('.like-icon');
                    const countSpan = button.querySelector('.like-count');

                    let count = parseInt(countSpan.textContent);

                    if (data.action === 'ajouter') {
                        img.src = './logos/coeurs.png';
                        countSpan.textContent = count + 1;
                    } else if (data.action === 'retirer') {
                        img.src = './logos/heart.png';
                        countSpan.textContent = count - 1;
                    }
                });
        });
    });



    function ouvremenutop() {
        les3trucs.classList.toggle("hidden");
    }
</script>

</html>