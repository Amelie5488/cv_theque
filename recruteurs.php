<?php
//Démarre une nouvelle session 
session_start();
require_once('php/config/config.php');

// connection a la bbd
$CV = new CV();
$CV->connexion();
if ($_SESSION["role"] == 0) {
    header("Location:candidats.php");
}

if (isset($_POST["sauces"])) {
    //si les champ nom/prenom/mail/naissance/portable sont vide (empty)
    if (empty($_POST["Nom"] && $_POST["Prenom"] && $_POST["mail"] && $_POST["naissance"] && $_POST["portable"])) {
        //alors ecrire
        echo "vide";
        //sinon
    } else {
        //un minimum de 5 competences a entrer et un maximum de 10 
        if (count($_POST['tags_new']) >= 5 && count($_POST['tags_new']) <= 10) {
            $today = getdate();
            $annee = explode('-', $_POST["naissance"]);
            $firstString = $annee[0];
            $age = ($today['year'] - $firstString);
            // création d'un candidat
            $requestDone = $CV->insertCandidat([
                "inputNom" => $_POST["Nom"],
                "inputPrenom" => $_POST["Prenom"],
                "inputAge" => "$age",
                "inputDate" => $_POST["naissance"],
                "inputAdresse" => $_POST["adresse"],
                "inputAdresse_1" => $_POST["adresse1"],
                "inputPostal" => $_POST["postal"],
                "inputVille" => $_POST["ville"],
                "inputPortable" => $_POST["portable"],
                "inputFixe" => $_POST["fixe"],
                "inputMail" => $_POST["mail"],
                "inputProfil" => "",
                "inputWeb" => "",
                "inputLink" => "",
                "inputVia" => "",
                "inputFace" => "",
                "inputCv" => ""
            ]);

            //recupere l'id MAX qui correspond au dernier ID donné
            $ID = $CV->getId();

            //on va boucler avec le nombre de TAG au total que nous avons 
            for ($i = 0; $i < count($_POST['tags_new']); $i++) {
                //création requete "UPDATE tablename SET Competence_$v = :inputTag$i where Id = :monID" => modif de la ligne -- id de la copetence est egal a l'id du tag -- ou l'id de la table est egal à l'ID de l'input
                //nous avons mis deux parametres dans la fonction ici $i / ["inputTag$i" => $_POST['tags_new'][$i], "monID" => $ID[0][0]] inputTag coorespond a ce qui a été entré dans l'encart nommé compétence (ex:Anglais) => le post correspond a ce que je recupere de l'encart nommé compétence donc les deux veulent dire la meme chose sauf que pour une question de sécurité je dois le nommer differement dans ma requete sql (:input)
                //$i correspond au nombre de fois que je vais boucler c'est a dire à mon nombre de compétences que j'ai au total            
                $requestInput = $CV->insertCompt($i, ["inputTag" => $_POST['tags_new'][$i], "monID" => $ID[0][0]]);
                //monNomCompt correspond au nom que j'entre dans tags_New (encart competences) fetch (dans la fonction) revoie 1 ou 0, 1 correspond a quelque chose d'existant donc si le nom tapé dans l'encart existe deja dans la table
                if ($CV->getCompetences(["monNomCompt" => $_POST['tags_new'][$i]]) > 0) {
                    //Alors je lance la requete insertTAG j'ajoute à ma table competence les nouveau nom de comptétence
                } else {
                    $requestDone = $CV->insertTag(["inputNom" => $_POST['tags_new'][$i]]);
                }
            }
            $succes ="Bienvenue chez Germa-Karrière, votre compte à bien été créé pour gérer votre carrière"; 
        } else {
            echo "Entrer au moins 5 compétences et au maximum 10";
        }
    }
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <script src="//cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.2.0/flowbite.min.css" rel="stylesheet" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.2.0/flowbite.min.js"></script>
    <script src="https://kit.fontawesome.com/f751d235a8.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/@yaireo/tagify"></script>
    <script src="https://cdn.jsdelivr.net/npm/@yaireo/tagify/dist/tagify.polyfills.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/tom-select/dist/css/tom-select.css" rel="stylesheet" />
    <title>CV Thèque</title>
    <style>
        select[name="myTable_length"] {
            width: 60px !important;
        }
    </style>
</head>

<body style="background: url('img/fond_candidats.webp');background-size:cover; background-repeat: no-repeat; background-position: center;">
    <header class="mb-10">
        <nav class="bg-white/70 border-gray-200 dark:bg-gray-900">
            <div class="w-[90%] flex flex-wrap items-center justify-between mx-auto p-4">
                <a href="index.php" class="flex flex-row items-center gap-3">
                    <i class="fa-solid fa-otter fa-2xl"></i>
                    <span class="self-center text-2xl hidden lg:flex font-semibold whitespace-nowrap dark:text-white">Germa-Karrière</span>
                </a>
                <button data-collapse-toggle="navbar-default" type="button" class="inline-flex items-center p-2 w-10 h-10 justify-center text-sm text-gray-500 rounded-lg md:hidden hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200 dark:text-gray-400 dark:hover:bg-gray-700 dark:focus:ring-gray-600" aria-controls="navbar-default" aria-expanded="false">
                    <span class="sr-only">Open main menu</span>
                    <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 17 14">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 1h15M1 7h15M1 13h15" />
                    </svg>
                </button>
                <div class="hidden w-full md:block md:w-auto" id="navbar-default">
                    <ul class="font-medium flex flex-col p-4 md:p-0 mt-4 border border-gray-100 rounded-lg md:flex-row md:space-x-8 rtl:space-x-reverse md:mt-0 md:border-0">
                        <li>
                            <a href="index.php" class="block py-2 px-3 text-white bg-blue-700 rounded md:bg-transparent md:text-blue-700 md:p-0 dark:text-white md:dark:text-blue-500" aria-current="page">Accueil</a>
                        </li>
                        <?php if (isset($_SESSION['email']) == true) { ?>
                            <?php if ($_SESSION['role'] == 1) { ?>
                                <li>
                                    <a href="recruteurs.php" class="block py-2 px-3 text-gray-900 rounded hover:bg-gray-100 md:hover:bg-transparent md:border-0 md:hover:text-blue-700 md:p-0 dark:text-white md:dark:hover:text-blue-500 dark:hover:bg-gray-700 dark:hover:text-white md:dark:hover:bg-transparent">Recruteurs</a>
                                </li>
                            <?php } ?>
                            <li>
                                <a href="candidats.php" class="block py-2 px-3 text-gray-900 rounded hover:bg-gray-100 md:hover:bg-transparent md:border-0 md:hover:text-blue-700 md:p-0 dark:text-white md:dark:hover:text-blue-500 dark:hover:bg-gray-700 dark:hover:text-white md:dark:hover:bg-transparent">Candidats</a>
                            </li>
                            <li>
                                <a href="deco.php" class="block py-2 px-3 text-gray-900 rounded hover:bg-gray-100 md:hover:bg-transparent md:border-0 md:hover:text-blue-700 md:p-0 dark:text-white md:dark:hover:text-blue-500 dark:hover:bg-gray-700 dark:hover:text-white md:dark:hover:bg-transparent">Déconnexion</a>
                            </li>
                        <?php } ?>
                    </ul>
                </div>
                <div class="hidden w-full md:block md:w-auto" id="navbar-default">
                    <ul class="font-medium flex flex-col p-4 md:p-0 mt-4 border border-gray-100 rounded-lg md:flex-row md:space-x-8 rtl:space-x-reverse md:mt-0 md:border-0">
                        <?php if (isset($_SESSION['email']) == true) { ?>
                            <li class="flex items-center">
                                <a class="block py-2 px-3 text-gray-900 rounded hover:bg-gray-100 md:hover:bg-transparent md:border-0 md:hover:text-blue-700 md:p-0 dark:text-white md:dark:hover:text-blue-500 dark:hover:bg-gray-700 dark:hover:text-white md:dark:hover:bg-transparent"><?= $_SESSION['email']; ?></a>
                            </li>
                        <?php } ?>
                        <li>
                            <!-- Modal toggle -->
                            <button data-modal-target="authentication-modal" data-modal-toggle="authentication-modal" class="block text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800" type="button">
                                Créer un candidat
                            </button>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
    </header>

    <!-- Modal formulaire creation de candidat-->

    <!-- Main modal -->
    <div id="authentication-modal" tabindex="-1" aria-hidden="true" class="fixed top-0 left-0 right-0 z-50 hidden w-full p-4 overflow-x-hidden overflow-y-auto md:inset-0 h-[calc(100%-1rem)] max-h-full">
        <div class="relative w-full max-w-4xl max-h-full">
            <!-- Modal content -->
            <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                <!-- Modal header -->
                <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600">
                    <h3 class="text-xl font-semibold text-gray-900 dark:text-white">
                        Création candidat
                    </h3>
                    <button type="button" class="end-2.5 text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white" data-modal-hide="authentication-modal">
                        <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                        </svg>
                        <span class="sr-only">Close modal</span>
                    </button>
                </div>
                <!-- Modal body -->
                <div class="p-4 md:p-5">
                    <form method="post" class="flex flex-col items-center w-[100%]" enctype="multipart/form-data">
                        <div class="flex flex-col md:flex-row gap-3 w-[100%] mb-5">
                            <div class="flex md:w-[50%]">
                                <span class="inline-flex items-center px-3 text-sm text-gray-900 bg-gray-200 border border-e-0 border-gray-300 rounded-s-md dark:bg-gray-600 dark:text-gray-400 dark:border-gray-600">
                                    <i class="fa-solid fa-user"></i>
                                </span>
                                <input type="text" class="rounded-none rounded-e-lg bg-gray-50 border border-gray-300 text-gray-900 focus:ring-blue-500 focus:border-blue-500 block flex-1 min-w-0 w-full text-sm p-2.5  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Nom" name="Nom" class="form-control" id="Nom" require>
                            </div>
                            <input type="text" class="rounded-md rounded-e-lg bg-gray-50 border border-gray-300 text-gray-900 focus:ring-blue-500 focus:border-blue-500 block flex-1 min-w-0 w-full text-sm p-2.5  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Prénom" type="text" name="Prenom" class="form-control" id="Prenom" require>
                        </div>

                        <div class="flex flex-col md:flex-row w-[100%] mb-5">
                            <span class="inline-flex items-center px-3 text-sm text-gray-900 bg-gray-200 border border-e-0 border-gray-300 rounded-s-md dark:bg-gray-600 dark:text-gray-400 dark:border-gray-600">
                                <i class="fa-solid fa-envelope"></i>
                            </span>
                            <input type="email" class="rounded-none rounded-e-lg bg-gray-50 border border-gray-300 text-gray-900 focus:ring-blue-500 focus:border-blue-500 block flex-1 min-w-0 w-full text-sm p-2.5  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Email" type="email" name="mail" class="form-control mb-3" id="Email1" require>
                        </div>

                        <div class="flex flex-col md:flex-row gap-3 w-[100%] mb-5">
                            <div class="flex md:w-[50%]">
                                <span class="inline-flex items-center px-3 text-sm text-gray-900 bg-gray-200 border border-e-0 border-gray-300 rounded-s-md dark:bg-gray-600 dark:text-gray-400 dark:border-gray-600">
                                    <i class="fa-solid fa-phone"></i>
                                </span>
                                <input class="rounded-none rounded-e-lg bg-gray-50 border border-gray-300 text-gray-900 focus:ring-blue-500 focus:border-blue-500 block flex-1 min-w-0 w-full text-sm p-2.5  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Tel Fixe" type="tel" name="fixe" class="form-control" id="fixe">
                            </div>
                            <input class="rounded-md rounded-e-lg bg-gray-50 border border-gray-300 text-gray-900 focus:ring-blue-500 focus:border-blue-500 block flex-1 min-w-0 w-full text-sm p-2.5  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Tel Portable" type="tel" name="portable" class="form-control" id="portable" require>
                        </div>


                        <div class="flex flex-col md:flex-row w-[100%] mb-5">
                            <span class="inline-flex items-center px-3 text-sm text-gray-900 bg-gray-200 border border-e-0 border-gray-300 rounded-s-md dark:bg-gray-600 dark:text-gray-400 dark:border-gray-600">
                                <i class="fa-solid fa-cake-candles"></i>
                            </span>
                            <input type="date" class="rounded-none rounded-e-lg bg-gray-50 border border-gray-300 text-gray-900 focus:ring-blue-500 focus:border-blue-500 block flex-1 min-w-0 w-full text-sm p-2.5  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Date de Naissance" type="date" name="naissance" class="form-control mb-3" id="naissance" require>
                        </div>

                        <div class="flex flex-col md:flex-row gap-3 w-[100%] mb-5">
                            <div class="flex md:w-[50%]">
                                <span class="inline-flex items-center px-3 text-sm text-gray-900 bg-gray-200 border border-e-0 border-gray-300 rounded-s-md dark:bg-gray-600 dark:text-gray-400 dark:border-gray-600">
                                    <i class="fa-solid fa-location-dot"></i>
                                </span>
                                <input class="rounded-none rounded-e-lg bg-gray-50 border border-gray-300 text-gray-900 focus:ring-blue-500 focus:border-blue-500 block flex-1 min-w-0 w-full text-sm p-2.5  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Adresse" type="text" name="adresse" class="form-control" id="adresse">
                            </div>
                            <input class="rounded-md rounded-e-lg bg-gray-50 border border-gray-300 text-gray-900 focus:ring-blue-500 focus:border-blue-500 block flex-1 min-w-0 w-full text-sm p-2.5  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Complément d'adresse" type="text" name="adresse1" class="form-control" id="adresse1">
                        </div>

                        <div class="flex flex-col md:flex-row gap-3 w-[100%] mb-5">
                            <div class="flex md:w-[50%]">
                                <span class="inline-flex items-center px-3 text-sm text-gray-900 bg-gray-200 border border-e-0 border-gray-300 rounded-s-md dark:bg-gray-600 dark:text-gray-400 dark:border-gray-600">
                                    <i class="fa-solid fa-location-dot"></i>
                                </span>
                                <input class="rounded-none rounded-e-lg bg-gray-50 border border-gray-300 text-gray-900 focus:ring-blue-500 focus:border-blue-500 block flex-1 min-w-0 w-full text-sm p-2.5  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Code Postal" type="text" name="postal" class="form-control" id="postal">
                            </div>
                            <input class="rounded-md rounded-e-lg bg-gray-50 border border-gray-300 text-gray-900 focus:ring-blue-500 focus:border-blue-500 block flex-1 min-w-0 w-full text-sm p-2.5  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Ville" type="text" name="ville" class="form-control" id="ville">
                        </div>

                        <div class="w-[100%] mb-10">
                            <div class="col-md-4">
                                <select placeholder="Compétences" class="block w-full rounded-sm cursor-pointer focus:outline-none" id="select-role" name="tags_new[]" multiple data-allow-new="true">
                                    <?php foreach ($CV->getCompetence() as $row) { ?>
                                        <option value="<?php print $row['Nom']; ?>"><?php print $row['Nom']; ?></option>
                                    <?php } ?>
                                </select>
                                <small class="text-muted opacity-25">Séléctionner vos compétences ou ajoutez les.</small>
                            </div>
                        </div>
                        <div class="">
                            <button type="submit" class="w-[250px] text-blue-700 hover:text-white border border-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center me-2 mb-2 dark:border-blue-500 dark:text-blue-500 dark:hover:text-white dark:hover:bg-blue-500 dark:focus:ring-blue-800" name="sauces">Créer</button>
                        </div>
                </div>

                </form>
            </div>
        </div>
    </div>
    </div>
    <main class="my-20 min-h-[80vh]">
        <section class="w-[90%] mx-auto mb-10 bg-white/90 p-10">
            <!-- tableau pour afficher tous les candidats -->
            <table id="myTable" class="table-auto border-separate">
                <thead class="bg-black text-white">
                    <th></th>
                    <th>Nom</th>
                    <th>Prenom</th>
                    <th>Age</th>
                    <th>Date_naissance</th>
                    <th>Adresse</th>
                    <th>Code_postal</th>
                    <th>ville</th>
                    <th>tel_portable</th>
                    <th>tel_fixe</th>
                    <th>Email</th>
                    <th>Profil</th>
                    <th>Competences</th>
                    <th>Site_Web</th>
                    <th>Profil_Linkedin</th>
                    <th>Profil_Viadeo</th>
                    <th>Profil_facebook</th>
                </thead>
                <tbody>
                    <?php
                    // recupere tous les candidats 
                    $touslescandidats = $CV->getAll();
                    foreach ($touslescandidats as $row) {
                        // associer chaque bouton supprimer avec l'ID du candidat // sinon supprime toute la table
                        if (isset($_POST["Supprimer" . $row['Id']])) {
                            // lancer la requete pour supprimer un candidat de la table en fonction de son ID
                            $SuppDone = $CV->deleteCandidat(["inputId" => $row['Id']]);
                        }

                        // associer le bouton sauces avec l'ID du candidats // sinon modifie toute la table 
                        if (isset($_POST["sauces_" .  $row['Id']])) {
                            // calcul de l'age 
                            $today = getdate();
                            $annee = explode('-', $_POST["naissance_" . $row['Id']]);
                            $firstString = $annee[0];
                            $age = ($today['year'] - $firstString);
                            // fonction qui permet de modifier les candidats 
                            $profil_Update = $CV->insertprofil(["inputNom" => $_POST["Nom_" . $row['Id']], "inputPrenom" => $_POST["Prenom_" . $row['Id']], "inputNaissance" => $_POST["naissance_" . $row['Id']], "inputAge" => $age, "inputAdresse" => $_POST["adresse_" . $row['Id']], "inputAdresse1" => $_POST["adresse1_" . $row['Id']], "inputPostal" => $_POST["postal_" . $row['Id']], "inputVille" => $_POST["ville_" . $row['Id']], "inputPortable" => $_POST["portable_" . $row['Id']], "inputFixe" => $_POST["fixe_" . $row['Id']], "inputMail" => $_POST["Email_" . $row['Id']], "inputProfil" => "", "monId" => $row['Id']]);
                        }
                    }
                    foreach ($touslescandidats as $row) {

                    ?>
                        <tr>
                            <td class="border-b border-black/50"> 
                                <button type="button" class="w-full text-purple-700 hover:text-white border border-purple-700 hover:bg-purple-800 focus:ring-4 focus:outline-none focus:ring-purple-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center me-2 mb-2 dark:border-purple-400 dark:text-purple-400 dark:hover:text-white dark:hover:bg-purple-500 dark:focus:ring-purple-900" name="Modif <?= $row["Id"]; ?>" data-modal-target="authentication-modal<?php print $row['Id'] ?>" data-modal-toggle="authentication-modal<?php print $row['Id'] ?>"> Modifier</button>
                                <form method="post">
                                    <button type="submit" class="w-full text-red-700 hover:text-white border border-red-700 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center me-2 mb-2 dark:border-red-500 dark:text-red-500 dark:hover:text-white dark:hover:bg-red-600 dark:focus:ring-red-900" name="Supprimer<?= $row["Id"]; ?>"> Supprimer</button>
                                </form>
                            </td>
                            <td class="border-b border-black/50"> <?= strtoupper($row["Nom"]) ?></td>
                            <td class="border-b border-black/50"> <?= $row["Prenom"] ?></td>
                            <td class="border-b border-black/50"> <?= $row["Age"] ?></td>
                            <td class="border-b border-black/50"> <?= $row["Date_naissance"] ?></td>
                            <td class="border-b border-black/50"> <?= $row["Adresse"] ?> <?= $row["Adresse_1"] ?></td>
                            <td class="border-b border-black/50"> <?= $row["Code_postal"] ?></td>
                            <td class="border-b border-black/50"> <?= $row["ville"] ?></td>
                            <td class="border-b border-black/50"> <?= $row["tel_portable"] ?></td>
                            <td class="border-b border-black/50"> <?= $row["tel_fixe"] ?></td>
                            <td class="border-b border-black/50"> <?= $row["Email"] ?></td>
                            <td class="border-b border-black/50"> <?= $row["Profil"] ?></td>
                            <td class="border-b border-black/50"> 
                                <?php
                                for ($i = 0; $i < 10; $i++) {
                                    $v = $i + 1;
                                    if ($row["Competence_$v"] != "") { ?>
                                        <span class="bg-indigo-100 text-indigo-800 text-xs font-medium me-2 px-2.5 py-0.5 rounded dark:bg-indigo-900 dark:text-indigo-300"><?php print $row["Competence_$v"] ?></span>
                                <?php }
                                } ?>
                            </td>
                            <td class="border-b border-black/50"> <?= $row["Site_Web"] ?></td>
                            <td class="border-b border-black/50"> <?= $row["Profil_Linkedin"] ?></td>
                            <td class="border-b border-black/50"> <?= $row["Profil_Viadeo"] ?></td>
                            <td class="border-b border-black/50"> <?= $row["Profil_facebook"] ?></td>
                        </tr>
                        <!-- Modal pour modifier chaque candidiat -->

                        <!-- Main modal -->
                        <div id="authentication-modal<?php print $row['Id'] ?>" tabindex="-1" aria-hidden="true" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
                            <div class="relative w-full max-w-4xl max-h-full">
                                <!-- Modal content -->
                                <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                                    <!-- Modal header -->
                                    <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600">
                                        <h3 class="text-xl font-semibold text-gray-900 dark:text-white">
                                            Modifier un candidat
                                        </h3>
                                        <button type="button" class="end-2.5 text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white" data-modal-hide="authentication-modal<?php print $row['Id'] ?>">
                                            <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                                            </svg>
                                            <span class="sr-only">Close modal</span>
                                        </button>
                                    </div>
                                    <!-- Modal body -->
                                    <div class="p-4 md:p-5">
                                        <form method="post" enctype="multipart/form-data">
                                            <div class="modal-body">

                                                <div class="mb-3">
                                                    <div class="d-flex md-flex-row gap-3 mb-3">

                                                        <div class="flex flex-col md:flex-row gap-3 w-[100%] mb-5">
                                                            <div class="flex md:w-[50%]">
                                                                <span class="inline-flex items-center px-3 text-sm text-gray-900 bg-gray-200 border border-e-0 border-gray-300 rounded-s-md dark:bg-gray-600 dark:text-gray-400 dark:border-gray-600">
                                                                    <i class="fa-solid fa-user"></i>
                                                                </span>
                                                                <input type="text" class="rounded-none rounded-e-lg bg-gray-50 border border-gray-300 text-gray-900 focus:ring-blue-500 focus:border-blue-500 block flex-1 min-w-0 w-full text-sm p-2.5  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Nom" name="Nom_<?= $row['Id'] ?>" id="Nom_<?= $row['Id'] ?>" value="<?= $row['Nom'] ?>">
                                                            </div>
                                                            <input type="text" class="rounded-md rounded-e-lg bg-gray-50 border border-gray-300 text-gray-900 focus:ring-blue-500 focus:border-blue-500 block flex-1 min-w-0 w-full text-sm p-2.5  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Prénom" type="text" name="Prenom_<?= $row['Id'] ?>" id="Prenom_<?= $row['Id'] ?>" value="<?= $row['Prenom'] ?>">
                                                        </div>

                                                        <div class="flex flex-col md:flex-row w-[100%] mb-5">
                                                            <span class="inline-flex items-center px-3 text-sm text-gray-900 bg-gray-200 border border-e-0 border-gray-300 rounded-s-md dark:bg-gray-600 dark:text-gray-400 dark:border-gray-600">
                                                                <i class="fa-solid fa-envelope"></i>
                                                            </span>
                                                            <input type="email" class="rounded-none rounded-e-lg bg-gray-50 border border-gray-300 text-gray-900 focus:ring-blue-500 focus:border-blue-500 block flex-1 min-w-0 w-full text-sm p-2.5  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Email" type="email" name="Email_<?= $row['Id'] ?>" id="Email_<?= $row['Id'] ?>" value="<?= $row['Email'] ?>">
                                                        </div>

                                                        <div class="flex flex-col md:flex-row gap-3 w-[100%] mb-5">
                                                            <div class="flex md:w-[50%]">
                                                                <span class="inline-flex items-center px-3 text-sm text-gray-900 bg-gray-200 border border-e-0 border-gray-300 rounded-s-md dark:bg-gray-600 dark:text-gray-400 dark:border-gray-600">
                                                                    <i class="fa-solid fa-phone"></i>
                                                                </span>
                                                                <input class="rounded-none rounded-e-lg bg-gray-50 border border-gray-300 text-gray-900 focus:ring-blue-500 focus:border-blue-500 block flex-1 min-w-0 w-full text-sm p-2.5  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Tel Fixe" type="tel" name="fixe_<?= $row['Id'] ?>" id="fixe_<?= $row['Id'] ?>" value="<?= $row['tel_fixe'] ?>">
                                                            </div>
                                                            <input class="rounded-md rounded-e-lg bg-gray-50 border border-gray-300 text-gray-900 focus:ring-blue-500 focus:border-blue-500 block flex-1 min-w-0 w-full text-sm p-2.5  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Tel Portable" type="tel" name="portable_<?= $row['Id'] ?>" id="portable_<?= $row['Id'] ?>" value="<?= $row['tel_portable'] ?>">
                                                        </div>

                                                        <div class="flex flex-col md:flex-row w-[100%] mb-5">
                                                            <span class="inline-flex items-center px-3 text-sm text-gray-900 bg-gray-200 border border-e-0 border-gray-300 rounded-s-md dark:bg-gray-600 dark:text-gray-400 dark:border-gray-600">
                                                                <i class="fa-solid fa-cake-candles"></i>
                                                            </span>
                                                            <input type="date" class="rounded-none rounded-e-lg bg-gray-50 border border-gray-300 text-gray-900 focus:ring-blue-500 focus:border-blue-500 block flex-1 min-w-0 w-full text-sm p-2.5  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Date de Naissance" type="date" name="naissance_<?= $row['Id'] ?>" id="naissance_<?= $row['Id'] ?>" value="<?= $row['Date_naissance'] ?>">
                                                        </div>

                                                        <div class="flex flex-col md:flex-row gap-3 w-[100%] mb-5">
                                                            <div class="flex md:w-[50%]">
                                                                <span class="inline-flex items-center px-3 text-sm text-gray-900 bg-gray-200 border border-e-0 border-gray-300 rounded-s-md dark:bg-gray-600 dark:text-gray-400 dark:border-gray-600">
                                                                    <i class="fa-solid fa-location-dot"></i>
                                                                </span>
                                                                <input class="rounded-none rounded-e-lg bg-gray-50 border border-gray-300 text-gray-900 focus:ring-blue-500 focus:border-blue-500 block flex-1 min-w-0 w-full text-sm p-2.5  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Adresse" type="text" name="adresse_<?= $row['Id'] ?>" id="adresse_<?= $row['Id'] ?>" value="<?= $row['Adresse'] ?>">
                                                            </div>
                                                            <input class="rounded-md rounded-e-lg bg-gray-50 border border-gray-300 text-gray-900 focus:ring-blue-500 focus:border-blue-500 block flex-1 min-w-0 w-full text-sm p-2.5  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Complément d'adresse" type="text" name="adresse1_<?= $row['Id'] ?>" id="adresse1_<?= $row['Id'] ?>" value="<?= $row['Adresse_1'] ?>">
                                                        </div>

                                                        <div class="flex flex-col md:flex-row gap-3 w-[100%] mb-5">
                                                            <div class="flex md:w-[50%]">
                                                                <span class="inline-flex items-center px-3 text-sm text-gray-900 bg-gray-200 border border-e-0 border-gray-300 rounded-s-md dark:bg-gray-600 dark:text-gray-400 dark:border-gray-600">
                                                                    <i class="fa-solid fa-location-dot"></i>
                                                                </span>
                                                                <input class="rounded-none rounded-e-lg bg-gray-50 border border-gray-300 text-gray-900 focus:ring-blue-500 focus:border-blue-500 block flex-1 min-w-0 w-full text-sm p-2.5  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Code Postal" type="text" name="postal_<?= $row['Id'] ?>" id="postal_<?= $row['Id'] ?>" value="<?= $row['Code_postal'] ?>">
                                                            </div>
                                                            <input class="rounded-md rounded-e-lg bg-gray-50 border border-gray-300 text-gray-900 focus:ring-blue-500 focus:border-blue-500 block flex-1 min-w-0 w-full text-sm p-2.5  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Ville" type="text" name="ville_<?= $row['Id'] ?>" id="ville_<?= $row['Id'] ?>" value="<?= $row['ville'] ?>">
                                                        </div>

                                                    </div>

                                                </div>
                                                <div class="modal-footer">
                                                    <button type="submit" class="w-full text-purple-700 hover:text-white border border-purple-700 hover:bg-purple-800 focus:ring-4 focus:outline-none focus:ring-purple-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center me-2 mb-2 dark:border-purple-400 dark:text-purple-400 dark:hover:text-white dark:hover:bg-purple-500 dark:focus:ring-purple-900" id="btn_<?php print $row['Id'] ?>" name="sauces_<?php print $row['Id'] ?>">Modifier</button>

                                                </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php

                    } ?>
                </tbody>

            </table>
        </section>
    </main>

    <?php include('footer.php'); ?>

    <script src="https://cdn.jsdelivr.net/npm/tom-select/dist/js/tom-select.complete.min.js"></script>
    <script>
        new TomSelect('#select-role', {
            maxItems: 10,
        });
    </script>
    <script src="js/script.js"></script>
</body>

</html>