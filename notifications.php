<?php
session_start();
if (!isset($_SESSION['pseudo'])) {
    header("Location: ./login.php");
    exit;
}
require_once "./utils/connexion.php";

$trucpafetcher = $db->query(" SELECT  messages.*,  utilisateurs.pseudo FROM messages JOIN utilisateurs  ON messages.utilisateur_id = utilisateurs.id ORDER BY messages.date ASC
");

$trucbon = $trucpafetcher->fetchAll(PDO::FETCH_ASSOC);




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
                    <a class="flex items-center gap-3 rounded-xl px-4 py-3 hover:bg-black/5" href="./messages.php">Messages</a>
                    <a class="flex items-center gap-3 rounded-xl px-4 py-3 hover:bg-black/5" href="./notifications.php">Notifications</a>
                    <a class="flex items-center gap-3 rounded-xl px-4 py-3 hover:bg-black/5" href="./tendances.php">Tendances</a>
                    <a class="flex items-center gap-3 rounded-xl px-4 py-3 hover:bg-black/5" href="./sauvegarde.php">Sauvegardé</a>
                    <a class="flex items-center gap-3 rounded-xl px-4 py-3 hover:bg-black/5" href="./profile.php">Profil</a>
                    <a class="flex items-center gap-3 rounded-xl px-4 py-3 hover:bg-black/5" href="./parametre.php">Paramètres</a>
                </nav>
            </aside>

            <main class="flex-1 space-y-6">
                <form action="./process/processchat.php" method="POST" enctype="multipart/form-data">
                    <div class="bg-white border border-[#E5E5E5] rounded-xl p-4">
                        <div class="bg-white border border-[#E5E5E5] rounded-xl p-6">
                            <div class="flex items-start gap-4">
                                <img src="./logos/enveloppe.svg" class="w-12 h-12 rounded-full" alt="">
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



                <?php foreach ($trucbon as $lesmessages): ?>

                    <?php if ($lesmessages['utilisateur_id'] === $_SESSION['user_id']): ?>

                        <article class="bg-white border border-[#E5E5E5] rounded-xl p-6">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-4">
                                    <img src="./logos/enveloppe.svg" class="w-12 h-12 rounded-full" alt="">
                                    <div>
                                        <div class="font-semibold"><?= htmlspecialchars($lesmessages['pseudo']) ?></div>
                                        <div class="text-sm text-gray-500">@<?= htmlspecialchars($lesmessages['nomutilisateur']) ?> ·<?= htmlspecialchars($lesmessages['date']); ?></div>
                                    </div>
                                </div>
                                <div class="text-gray-400 cursor-pointer">•••</div>
                            </div>
                            <p class="mt-4 text-gray-800"> <?= htmlspecialchars($lesmessages['message']); ?></p>
                            <?php if (!empty($lesmessages['fichier'])): ?>
                                <div class="mt-4 rounded-lg overflow-hidden">
                                    <img src="<?= htmlspecialchars($lesmessages['fichier']) ?>" class="w-full h-64 object-fit" alt="paysage">
                                </div>
                            <?php endif; ?>
                        </article>
                    <?php endif; ?>

                    <?php if ($lesmessages['utilisateur_id'] !== $_SESSION['user_id']): ?>
                        <article class="bg-white border border-[#E5E5E5] rounded-xl p-6">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-4">
                                    <img src="./logos/enveloppe.svg" class="w-12 h-12 rounded-full" alt="">
                                    <div>
                                        <div class="font-semibold"><?= htmlspecialchars($lesmessages['pseudo']) ?></div>
                                        <div class="text-sm text-gray-500">@<?= htmlspecialchars($lesmessages['nomutilisateur']) ?> ·<?= htmlspecialchars($lesmessages['date']); ?></div>
                                    </div>
                                </div>
                                <div class="text-gray-400 cursor-pointer">•••</div>
                            </div>
                            <p class="mt-4 text-gray-800"> <?= htmlspecialchars($lesmessages['message']); ?></p>
                            <?php if (!empty($lesmessages['fichier'])): ?>
                                <div class="mt-4 rounded-lg overflow-hidden">
                                    <img src="<?= htmlspecialchars($lesmessages['fichier']) ?>" class="w-full h-64 object-fit" alt="paysage">
                                </div>
                            <?php endif; ?>

                        </article>
                    <?php endif; ?>

                <?php endforeach; ?>










            </main>
        </div>
    </div>

    <a class="text-black" href="./process/processdeconnectecompte.php">deconnecte toi ici</a>
</body>

<script>
    (function() {
        const fileInput = document.getElementById('uploadimg');
        const fileLabel = document.getElementById('file_label');
        const fileName = document.getElementById('file_name');
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
</script>

</html>