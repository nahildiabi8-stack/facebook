<?php
require_once './utils/connexion.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: ./login.php");
    exit;
}

$lid = (int) $_SESSION['user_id'];


if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $pseudo = trim($_POST['pseudo']);
    $nomutilisateur = trim($_POST['nomutilisateur']);
    $filePath = null;


    if (!empty($_FILES['uploadimg']['name'])) {

        $dossier = __DIR__ . "/profilepicture/";
        if (!is_dir($dossier)) {
            mkdir($dossier, 0755, true);
        }

        $extension = pathinfo($_FILES['uploadimg']['name'], PATHINFO_EXTENSION);
        $nomdufichier = uniqid('pp_') . '.' . $extension;
        $destination = $dossier . $nomdufichier;

        if (move_uploaded_file($_FILES['uploadimg']['tmp_name'], $destination)) {
            $filePath = "profilepicture/" . $nomdufichier;
        }
    }

  
    if ($filePath) {
        $sql = "UPDATE utilisateurs 
                SET pseudo = :pseudo,
                    nomutilisateur = :nomutilisateur,
                    profilepicture = :profilepicture
                WHERE id = :id";
    } else {
        $sql = "UPDATE utilisateurs 
                SET pseudo = :pseudo,
                    nomutilisateur = :nomutilisateur
                WHERE id = :id";
    }

    $stmt = $db->prepare($sql);

    $params = [
        'pseudo' => $pseudo,
        'nomutilisateur' => $nomutilisateur,
        'id' => $lid
    ];

    if ($filePath) {
        $params['profilepicture'] = $filePath;
    }

    $stmt->execute($params);

    $_SESSION['pseudo'] = $pseudo;
    $_SESSION['nomutilisateur'] = $nomutilisateur;

    header("Location: ./accueil.php");
    exit;
}

$stmt = $db->prepare("SELECT * FROM utilisateurs WHERE id = :id");
$stmt->execute(['id' => $lid]);
$legars = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$legars) {
    die("non");
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="./src/output.css">
</head>

<body class=" bg-linear-to-r from-[#F1F6FF] to-[#A40DFF]/35 p-8">


    <header class="text-center flex flex-col gap-4 pb-8 justify-center items-center">
        <h1
            class=" pt-8 text-7xl font-bold bg-linear-to-r from-[#7E39FB] to-[#B32ACC]   text-transparent bg-clip-text ">
            Qwerty
        </h1>
        <p class="text-[#717182] text-2xl">
            Connecter vous avec le monde
        </p>
    </header>

    <section class=" flex flex-col  items-start justify-start gap-8 pb-16">

        <div class="flex flex-col gap-4 w-full hover:bg-gray-400/10 rounded-2xl p-4 cursor-pointer">

            <div
                class="transition delay-25 duration-300 ease-out hover:-translate-y-1 hover:scale-110 w-16 h-16 flex justify-center items-center bg-[#2576FF] rounded-2xl">
                <svg class="w-8 h-8 fill-white" xmlns="http://www.w3.org/2000/svg" version="1.0"
                    viewBox="0 0 512.000000 512.000000" preserveAspectRatio="xMidYMid meet">

                    <g transform="translate(0.000000,512.000000) scale(0.100000,-0.100000)" stroke="none">
                        <path
                            d="M831 4464 c-167 -45 -292 -172 -336 -341 -22 -86 -22 -2240 0 -2326 44 -171 170 -297 340 -342 49 -12 142 -15 540 -16 l480 0 303 -360 c175 -208 315 -365 333 -375 40 -20 108 -20 148 0 17 9 158 168 332 376 l303 360 476 0 c515 0 535 2 642 56 67 34 158 125 192 192 58 115 56 69 56 1272 0 948 -2 1114 -15 1163 -44 171 -170 297 -340 342 -87 22 -3370 22 -3454 -1z m3405 -320 c19 -9 44 -31 57 -48 l22 -31 3 -1093 c3 -1211 6 -1136 -62 -1185 -31 -22 -32 -22 -571 -27 -481 -4 -544 -7 -566 -22 -14 -9 -144 -156 -289 -327 -145 -171 -266 -311 -270 -311 -4 0 -125 140 -270 311 -145 171 -275 318 -289 327 -22 15 -85 18 -566 22 -539 5 -540 5 -571 27 -68 49 -65 -26 -62 1185 l3 1093 22 31 c49 69 -87 64 1731 64 1496 0 1647 -1 1678 -16z" />
                    </g>
                </svg>
            </div>
            <p class="text-black  font-bold text-2xl">
                Messages instantanés
            </p>
            <p>
                Restez en contact avec vos amis en temps réel
            </p>
        </div>


        <div class="flex flex-col gap-4 w-full hover:bg-gray-400/10 rounded-2xl p-4 cursor-pointer">

            <div
                class="transition delay-25 duration-300 ease-out hover:-translate-y-1 hover:scale-110  w-16 h-16 flex justify-center items-center bg-[#A73BFF] rounded-2xl">
                <svg class="w-8 h-8 fill-white" xmlns="http://www.w3.org/2000/svg" version="1.0"
                    viewBox="0 0 512.000000 512.000000" preserveAspectRatio="xMidYMid meet">

                    <g transform="translate(0.000000,512.000000) scale(0.100000,-0.100000)" stroke="none">
                        <path
                            d="M866 4319 c-62 -15 -153 -68 -197 -116 -22 -24 -55 -74 -72 -111 l-32 -67 0 -1471 0 -1470 37 -76 c45 -91 103 -147 196 -191 l67 -32 1696 0 1695 0 76 37 c91 45 147 103 191 196 l32 67 0 1470 0 1470 -32 67 c-44 93 -100 151 -191 196 l-76 37 -1675 2 c-990 0 -1692 -3 -1715 -8z m3368 -161 c55 -16 138 -99 154 -154 9 -31 12 -303 12 -1125 l0 -1084 -583 583 c-544 544 -585 582 -617 582 -32 0 -57 -23 -337 -302 l-303 -303 -463 463 c-431 431 -465 462 -497 462 -32 0 -64 -29 -457 -422 l-423 -423 0 764 c0 570 3 774 12 805 15 51 99 137 148 153 53 17 3298 17 3354 1z m-2169 -1538 c429 -428 463 -460 495 -460 32 0 58 23 337 302 l303 303 600 -600 600 -600 0 -209 c0 -135 -4 -223 -12 -250 -16 -55 -99 -138 -154 -154 -60 -17 -3288 -17 -3348 0 -55 16 -138 99 -154 154 -9 30 -12 184 -12 570 l0 529 437 437 c241 241 440 438 443 438 3 0 212 -207 465 -460z" />
                        <path
                            d="M3426 3910 c-63 -16 -153 -70 -197 -117 -22 -24 -55 -74 -72 -111 -29 -61 -32 -76 -32 -163 0 -90 2 -99 37 -171 45 -91 103 -147 196 -191 61 -29 76 -32 162 -32 86 0 101 3 162 32 93 44 151 100 196 191 35 72 37 81 37 172 0 91 -2 100 -37 172 -68 136 -188 217 -336 224 -42 2 -94 -1 -116 -6z m168 -162 c86 -26 166 -136 166 -228 0 -124 -116 -240 -240 -240 -63 0 -114 23 -165 75 -102 101 -102 229 0 330 70 71 145 90 239 63z" />
                    </g>
                </svg>
            </div>

            <p class="text-black  font-bold text-2xl">
                Messages instantanés
            </p>
            <p>
                Restez en contact avec vos amis en temps réel
            </p>
        </div>

        <div class="flex flex-col gap-4 hover:bg-gray-400/10 rounded-2xl w-full p-4 cursor-pointer  ">

            <div
                class="transition delay-25 duration-300 ease-out hover:-translate-y-1 hover:scale-110 w-16 h-16 flex justify-center items-center bg-[#F22690] rounded-2xl">
                <svg class="w-8 h-8 fill-white" xmlns="http://www.w3.org/2000/svg" version="1.0"
                    viewBox="0 0 512.000000 512.000000" preserveAspectRatio="xMidYMid meet">

                    <g transform="translate(0.000000,512.000000) scale(0.100000,-0.100000)" stroke="none">
                        <path
                            d="M2380 5114 c-19 -2 -78 -9 -130 -14 -449 -49 -939 -257 -1305 -555 -114 -92 -294 -274 -384 -387 -240 -300 -431 -704 -505 -1068 -41 -201 -50 -302 -50 -530 0 -228 9 -329 50 -530 76 -371 272 -781 519 -1085 92 -114 274 -294 387 -384 300 -240 704 -431 1068 -505 201 -41 302 -50 530 -50 228 0 329 9 530 50 278 57 634 205 873 363 389 259 688 599 893 1016 128 261 207 525 246 815 16 123 16 497 0 620 -39 290 -118 554 -246 815 -134 273 -283 480 -498 692 -210 209 -403 346 -673 479 -258 126 -526 208 -805 244 -88 12 -438 21 -500 14z m520 -189 c399 -60 778 -218 1099 -458 123 -92 376 -345 468 -468 241 -322 398 -701 459 -1104 25 -167 25 -503 0 -670 -61 -403 -218 -782 -459 -1104 -92 -123 -345 -376 -468 -468 -322 -241 -701 -398 -1104 -459 -167 -25 -503 -25 -670 0 -403 61 -782 218 -1104 459 -123 92 -376 345 -468 468 -241 322 -398 701 -459 1104 -25 167 -25 503 0 670 61 403 218 782 459 1104 92 123 345 376 468 468 354 265 769 426 1214 472 123 13 437 5 565 -14z" />
                        <path
                            d="M2920 3457 c-435 -178 -734 -306 -765 -327 -66 -45 -125 -106 -169 -171 -46 -71 -616 -1469 -616 -1513 0 -42 35 -76 77 -76 43 0 1443 571 1512 616 68 45 130 107 175 175 46 71 616 1469 616 1513 0 44 -35 76 -81 75 -21 0 -313 -114 -749 -292z m587 41 c-86 -218 -477 -1168 -497 -1208 -35 -68 -109 -144 -175 -178 -61 -31 -1217 -503 -1222 -499 -4 5 468 1161 499 1222 33 64 109 140 173 173 50 25 1202 500 1219 501 4 1 6 -5 3 -11z" />
                        <path
                            d="M2465 2887 c-97 -33 -172 -98 -214 -187 -22 -46 -26 -69 -26 -140 0 -103 27 -170 96 -239 69 -69 136 -96 239 -96 103 0 170 27 239 96 69 69 96 136 96 239 0 71 -4 94 -26 140 -33 70 -99 136 -168 168 -66 30 -175 39 -236 19z m175 -180 c51 -27 90 -90 90 -147 0 -87 -83 -170 -170 -170 -87 0 -170 83 -170 170 0 57 39 120 90 147 55 29 105 29 160 0z" />
                    </g>
                </svg>
            </div>
            <p class="text-black  font-bold text-2xl">
                Messages instantanés
            </p>
            <p>
                Restez en contact avec vos amis en temps réel
            </p>


        </div>
    </section>
    <form method="POST" enctype="multipart/form-data">

        <div class="flex flex-col justify-center items-center ">
            <section class="items-center justify-center text-center pt-8 bg-white shadow-2xl w-120 h-152 rounded-2xl">
                <div class="flex flex-col gap-2">
                    <h2 class="text-black text-4xl font-bold">
                        Changer vos identifiants
                    </h2>

                </div>
                <section class=" flex flex-col justify-center items-center text-center p-8 pt-16 gap-4 ">
                    <p class="font-bold text-start">
                        Nouveau pseudo
                    </p>
                    <div class="flex flex-col">

                        <input required placeholder="Ton nouveau pseudo.."
                            class="bg-gray-400/10 w-96 h-12 rounded-2xl transition delay-150 duration-300 ease-in-out  hover:border-6 border-gray-400 p-4"
                            type="text" name="pseudo" id="pseudo">
                    </div>
                    <p class="font-bold">
                        Nouveau nom d'utilisateur
                    </p>

                    <div class="flex flex-col">

                        <input required placeholder="Ton nouveau nom.."
                            class="bg-gray-400/10 w-96 h-12 rounded-2xl transition delay-150 duration-300 ease-in-out  hover:border-6 border-gray-400 p-4"
                            type="text" name="nomutilisateur" id="nomutilisateur">
                    </div>

                    <p class="font-bold">
                        Nouvelle photo de profil
                    </p>
                    <div class="flex flex-col">

                        <input required placeholder="Ta nouvelle photo.."
                            class="bg-gray-400/10 w-96 h-12 rounded-2xl transition delay-150 duration-300 ease-in-out  hover:border-6 border-gray-400 p-4"
                            type="file" name="uploadimg" id="uploadimg">
                    </div>


                    <form action="./accueil.php">


                        <div class="text-center text-white pt-1">
                            <button class="bg-[#030213] w-96 h-13 rounded-2xl cursor-pointer" type="submit">
                                Changer
                            </button>
                            <div class="flex flex-row pt-4 gap-4 text-center justify-center items-center">
                                <p class=" text-[#717182]">

                                    <a href="./accueil.php" class="text-[#155DFC] cursor-pointer">Revenez en arrière </a>
                                </p>
                            </div>
                    </form>

        </div>
        </section>
        </section>
        </div>
    </form>
</body>

</html>