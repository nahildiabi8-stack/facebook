<?php
session_start();
if (!isset($_SESSION['pseudo'])) {
    header("Location: ./login.php");
    exit;
}
require_once "./utils/connexion.php";

$trucpafetcher = $db->query(" SELECT  messages.*,  utilisateurs.pseudo FROM messages JOIN utilisateurs  ON messages.utilisateur_id = utilisateurs.id ORDER BY messages.date DESC
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
<section class="justify-end items-end content-end">

    <body>
        <div class="min-h-screen bg-gray-50">
            <div class="max-w-7xl mx-auto px-6 py-8 flex gap-8">
                <aside class="w-80 bg-white border border-[#E5E5E5] rounded-lg p-6">
                    <h1 class="text-4xl font-extrabold bg-linear-to-r from-[#7E39FB] to-[#B32ACC] text-transparent bg-clip-text">Qwerty</h1>
                    <nav class="mt-8 flex flex-col gap-4">
                        <a class="flex items-center gap-3 bg-[#050316] text-white rounded-xl px-4 py-3" href="./accueil.php">Accueil</a>
                        <a class="flex items-center gap-3 rounded-xl px-4 py-3 hover:bg-black/5" href="./explorer.php">Explorer</a>
                        <a class="flex items-center gap-3 rounded-xl px-4 py-3 hover:bg-black/5" href="./tendances.php">Tendances</a>
                        <a class="flex items-center gap-3 rounded-xl px-4 py-3 hover:bg-black/5" href="./parametre.php">Profil</a>
                        <a class="flex items-center gap-3 rounded-xl px-4 py-3 hover:bg-black/5" href="./process/processdeconnectecompte.php">Déconnecte toi</a>
                    </nav>
                </aside>
                <main class="flex-1 space-y-6">


                    <section class=" grid grid-cols-2 w-full h-screen justify-end items-end content-end">
                        <?php foreach ($trucbon as $lesmessages): ?>

                            <?php if ($lesmessages['utilisateur_id'] === $_SESSION['user_id']): ?>

                                <article class="  bg-white border border-[#E5E5E5] transition delay-25 rounded-xl p-6 hover:shadow  hover:bg-gray-400 hover:text-2xl hover:text-white">
                                    <div class="flex items-center justify-between mb-3">
                                        <div class="font-semibold text-sm"><?= htmlspecialchars($lesmessages['pseudo']) ?></div>
                                        <div class="relative">
                                            <button onclick="toggleMenu(event, this)" class="text-gray-400 cursor-pointer hover:text-gray-600">•••</button>
                                            <div class="hidden absolute right-0 mt-2 w-32 bg-white border border-gray-300 rounded-lg shadow-lg z-10 menu-dropdown">
                                                <button onclick="confirmDelete(<?= $lesmessages['id'] ?>)" class="block w-full text-left px-4 py-2 text-red-600 hover:bg-red-50 text-sm">Supprimer</button>
                                            </div>
                                        </div>
                                    </div>

                                    <?php if (!empty($lesmessages['fichier'])): ?>
                                        <div class=" flex flex-row mt-4 rounded-lg overflow-hidden  hover:text-2xl hover:text-white">
                                            <button class="like-button" data-post-id="<?= $lesmessages['id'] ?>">
                                                <img class="w-6 h-6 like-icon" src="./logos/heart.png" alt="Like">
                                                <span class="like-count"><?= (int)$lesmessages['likes'] ?></span>
                                            </button>
                                            <img src="<?= htmlspecialchars($lesmessages['fichier']) ?>" class="w-full h-64 object-fit" alt="paysage">
                                        </div>
                                </article>

                            <?php endif; ?>



                        <?php endif; ?>

                        <?php if ($lesmessages['utilisateur_id'] !== $_SESSION['user_id']): ?>
                            <article class="  bg-white border border-[#E5E5E5] transition delay-25 rounded-xl p-6 hover:shadow  hover:bg-gray-400 hover:text-2xl hover:text-white">
                                <div class="flex items-center justify-between mb-3">
                                    <div class="font-semibold text-sm"><?= htmlspecialchars($lesmessages['pseudo']) ?></div>
                                    <div class="relative">
                                        <button onclick="toggleMenu(event, this)" class="text-gray-400 cursor-pointer hover:text-gray-600">•••</button>
                                        <div class="hidden absolute right-0 mt-2 w-32 bg-white border border-gray-300 rounded-lg shadow-lg z-10 menu-dropdown">
                                            <button onclick="confirmDelete(<?= $lesmessages['id'] ?>)" class="block w-full text-left px-4 py-2 text-red-600 hover:bg-red-50 text-sm">Supprimer</button>
                                        </div>
                                    </div>
                                </div>

                                <?php if (!empty($lesmessages['fichier'])): ?>
                                    <div class=" flex flex-row mt-4 rounded-lg overflow-hidden  hover:text-2xl hover:text-white">
                                        <button class="like-button" data-post-id="<?= $lesmessages['id'] ?>">
                                            <img class="w-6 h-6 like-icon" src="./logos/heart.png" alt="Like">
                                            <span class="like-count"><?= (int)$lesmessages['likes'] ?></span>
                                        </button>
                                        <img src="<?= htmlspecialchars($lesmessages['fichier']) ?>" class="w-full h-64 object-fit" alt="paysage">
                                    </div>
                            </article>
                        <?php endif; ?>

                    <?php endif; ?>

                <?php endforeach; ?>


                    </section>
                </main>

                <body>



            </div>

</html>

<script>
g
    function toggleMenu(event, button) {
        event.stopPropagation();
        const menu = button.nextElementSibling;
        const allMenus = document.querySelectorAll('.menu-dropdown');
        
        allMenus.forEach(m => {
            if (m !== menu) {
                m.classList.add('hidden');
            }
        });
        
        menu.classList.toggle('hidden');
    }

    
    document.addEventListener('click', () => {
        document.querySelectorAll('.menu-dropdown').forEach(menu => {
            menu.classList.add('hidden');
        });
    });

   
    function confirmDelete(messageId) {
        if (confirm('Êtes-vous sûr de vouloir supprimer ce message ?')) {
            fetch('./process/processDeleteMessage.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: 'message_id=' + encodeURIComponent(messageId)
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                } else {
                    alert('uh oh ' + (data.error || 'peut pas'));
                }
            })
            .catch(error => {
                console.error('uh oh', error);
                alert('peut pas uspprimer le msg');
            });
        }
    }

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
</script>