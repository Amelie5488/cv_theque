<?php
//Démarre une nouvelle session 
session_start();
require_once('php/config/config.php');

// connection a la bbd
$CV = new CV();
$CV->connexion();

// variable pour les alerte lors de la connection 
$error = "";
$succes = "";

// pour pouvoir etre sur la page candidat j'ai besoin d'etre connecté à une session 
if (isset($_SESSION['email']) == true) {
    //le mail de ma BDD correspon au mail de ma session 
    $result = $CV->gettout(["inputMail" => $_SESSION['email']]);
}

if (isset($_POST["sauces"])) {
    //si les champ nom/prenom/mail/naissance/portable sont vide (empty)
    if (empty($_POST["Nom1"] && $_POST["Prenom1"] && $_POST["mail1"] && $_POST["naissance1"] && $_POST["portable1"])) {
        //alors alerte
        $error =  "Certains champs sont vide";
        //sinon
    } else {
        //un minimum de 5 competences a entrer et un maximum de 10 
        if (count($_POST['tags_new']) >= 5 && count($_POST['tags_new']) <= 10) {
            //chemin pour upload les cv
            $uploadDirectory = "uploads/";

            $errors = []; // Store errors here

            $fileExtensionsAllowed = ['pdf', 'docx']; // extension 

            $filename   = uniqid() . "-" . time(); // 5dab1961e93a7-1571494241
            $fileSize = $_FILES['the_file']['size'];
            $fileType = pathinfo($_FILES["the_file"]["name"], PATHINFO_EXTENSION);
            $basename   = $filename . "." . $fileType; // 5dab1961e93a7_1571494241.jpg
            $fileTmpName  = $_FILES['the_file']['tmp_name'];
            $fileExtension = explode('.', $basename);
            $file_extension = end($fileExtension);

            $uploadPath = $uploadDirectory . $basename;

            if (!in_array($file_extension, $fileExtensionsAllowed)) {
                $errors[] = "Seul les fichiers PDF et DOCX sont autorisé";
            }

            if ($fileSize > 4000000) {
                $errors[] = "File exceeds maximum size (4MB)";
            }

            if (empty($errors)) {
                $didUpload = move_uploaded_file($fileTmpName, $uploadPath);  // Déplace un fichier téléchargé

                //calcul de l'age
                if ($didUpload) {
                    //renvoie la date du jour
                    $today = getdate();
                    $annee = explode('-', $_POST["naissance1"]);  // explode — Scinde une chaîne de caractères en segments
                    // renvoie 2023 juste l'année
                    $firstString = $annee[0];
                    $age = ($today['year'] - $firstString);

                    // création d'un candidat
                    $requestDone = $CV->insertCandidat(["inputNom" => $_POST["Nom1"], "inputPrenom" => $_POST["Prenom1"], "inputAge" => $age, "inputDate" => $_POST["naissance1"], "inputAdresse" => $_POST["adresse"], "inputAdresse_1" => $_POST["adresse1"], "inputPostal" => $_POST["postal"], "inputVille" => $_POST["ville"], "inputPortable" => $_POST["portable1"], "inputFixe" => $_POST["fixe"], "inputMail" => $_POST["mail1"], "inputProfil" => $_POST['domaine'], "inputWeb" => "", "inputLink" => "", "inputVia" => "", "inputFace" => "", "inputCv" => $uploadPath]);

                    //recupere l'id MAX qui correspond au dernier ID donné
                    $ID = $CV->getId();
                    $requestDone = $CV->insertId(["monMail" => $_SESSION["email"], "inputId" => $ID[0][0]]);
                    //on va boucler avec le nombre de TAG au total que nous avons 
                    for ($i = 0; $i < count($_POST['tags_new']); $i++) {
                        //création requete "UPDATE tablename SET Competence_$v = :inputTag$i where Id = :monID" => modif de la ligne -- id de la copetence est egal a l'id du tag -- ou l'id de la table est egal à l'ID de l'input
                        //nous avons mis deux parametres dans la fonction ici $i / ["inputTag$i" => $_POST['tags_new'][$i], "monID" => $ID[0][0]] inputTag coorespond a ce qui a été entré dans l'encart nommé compétence (ex:Anglais) => le post correspond a ce que je recupere de l'encart nommé compétence donc les deux veulent dire la meme chose sauf que pour une question de sécurité je dois le nommer differement dans ma requete sql (:input)
                        //$i correspond au nombre de fois que je vais boucler c'est a dire à mon nombre de compétences que j'ai au total            
                        $requestInput = $CV->insertCompt($i, ["inputTag" => $_POST['tags_new'][$i], "monID" => $ID[0][0]]);

                    }
                } else {
                    $error = "erreur merci de contacter le tout puissant";
                }
            } else {
                foreach ($errors as $error) {
                    $error = "These are the errors" . "\n";
                }
            }
        } else {
            $error = "Entrer au moins 5 compétences et au maximum 10";
        }
    }
}


/// UPDATE INFO
if (isset($_POST["sauces_profil"])) {
    // Si les champs nom/prenom/email/adresse/telephone sont vide 
    if (empty($_POST["n1"] && $_POST["p1"] && $_POST["e1"] && $_POST["d1"] && $_POST["t1"])) {
        //alors alerte
        $error =  "Certains champs sont vide";
        //sinon
    } else {

        $result = $CV->gettout(["inputMail" => $_SESSION['email']]);
        // creation de tag 
        $tagsTab = explode(",", $_POST['Mytags']);  // le renvoi sous forme de tableau
        $tab_replace = ['{', '"', 'value', ':', '}', '[', ']']; // retire le mot value 
        $tagsTab = str_replace($tab_replace, "", $tagsTab); // le remplace par vide 
        // mise a jour des competence et insertion quand une competence n'existe pas dans la base 
        // deux boucles sont neccessaire car il met a jour le nombre de competence quand on en supprime une mais aussi quand on en ajoute une
        // la premiere boucle part des 9 competences la seconde part de zero meme si nous ajoutons ou supprimons une competence avec count($tagsTab) il sait combien de competence il doit parcourir 

        for ($i = 9; $i >= count($tagsTab); $i--) { 
            $requestInput = $CV->insertCompt($i, ["inputTag" => "", "monID" => $result['profil_id']]);
        }
        for ($i = 0; $i < count($tagsTab); $i++) {
            //création requete "UPDATE tablename SET Competence_$v = :inputTag$i where Id = :monID" => modif de la ligne -- id de la copetence est egal a l'id du tag -- ou l'id de la table est egal à l'ID de l'input
            //nous avons mis deux parametres dans la fonction ici $i / ["inputTag$i" => $_POST['tags_new'][$i], "monID" => profil_id inputTag coorespond a ce qui a été entré dans l'encart nommé compétence (ex:Anglais) => le post correspond a ce que je recupere de l'encart nommé compétence donc les deux veulent dire la meme chose sauf que pour une question de sécurité je dois le nommer differement dans ma requete sql (:input)
            //$i correspond au nombre de fois que je vais boucler c'est a dire à mon nombre de compétences que j'ai au total 
            $requestInput = $CV->insertCompt($i, ["inputTag" => $tagsTab[$i], "monID" => $result['profil_id']]);

            //monNomCompt correspond au nom que j'entre dans tags_new (encart competences) fetch (dans la fonction) revoie 1 ou 0, 1 correspond a quelque chose d'existant donc si le nom tapé dans l'encart existe deja dans la table
            if ($CV->getCompetences(["monNomCompt" =>  $tagsTab[$i]]) > 0) {
                //Alors je lance la requete insertTAG j'ajoute à ma table competence les nouveau nom de comptétence
            } else {
                $requestDone = $CV->insertTag(["inputNom" =>  $tagsTab[$i]]);
            }
        }
        // calcul de l'age 
        $today = getdate();
        $annee = explode('-', $_POST["d1"]);
        $firstString = $annee[0];
        $age = ($today['year'] - $firstString);
        $profil_Update = $CV->insertprofil1(["inputNom" => $_POST["n1"], "inputPrenom" => $_POST["p1"], "inputNaissance" => $_POST["d1"], "inputAge" => $age, "inputAdresse" => $_POST["ad1"], "inputAdresse1" => $_POST["add1"], "inputPostal" => $_POST["po1"], "inputVille" => $_POST["v1"], "inputPortable" => $_POST["t1"], "inputFixe" => $_POST["f1"], "inputMail" => $_POST["e1"], "inputProfil" => $_POST["pr1"], "inputWeb" => $_POST["s1"], "inputLink" => $_POST["s2"], "inputVid" => $_POST["s3"], "inputFB" => $_POST["s4"], "monId" => $result['profil_id']]);
    }
}
// UPDATE CV
if (isset($_POST['upCV'])) {
    //chemin
    $uploadDirectory = "uploads/";

    $errors = []; // Store errors here

    $fileExtensionsAllowed = ['pdf', 'docx']; // extension 

    $filename   = uniqid() . "-" . time(); // 5dab1961e93a7-1571494241
    $fileSize = $_FILES['monCV']['size'];
    $fileType = pathinfo($_FILES["monCV"]["name"], PATHINFO_EXTENSION);
    $basename   = $filename . "." . $fileType; // 5dab1961e93a7_1571494241.jpg
    $fileTmpName  = $_FILES['monCV']['tmp_name'];
    $fileExtension = explode('.', $basename);
    $file_extension = end($fileExtension);

    $uploadPath = $uploadDirectory . $basename;

    if (!in_array($file_extension, $fileExtensionsAllowed)) {
        $errors[] = "Seul les PDF sont autorisé";
    }

    if ($fileSize > 4000000) {
        $errors[] = "File exceeds maximum size (4MB)";
    }

    if (empty($errors)) {
        $didUpload = move_uploaded_file($fileTmpName, $uploadPath);  // Déplace un fichier téléchargé

        //calcul de l'age
        if ($didUpload) {
            $done =  $CV->updateCV(["inputCV" => $uploadPath, "monId" => $result['profil_id']]);
        } else {
            $error = "erreur merci de contacter le tout puissant";
        }
    } else {
        foreach ($errors as $error) {
            $error;
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.2.0/flowbite.min.css" rel="stylesheet" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.2.0/flowbite.min.js"></script>
    <script src="js/app.js"></script>
    <script src="https://kit.fontawesome.com/f751d235a8.js" crossorigin="anonymous"></script>
    <title>Document</title>
    <script src="https://cdn.jsdelivr.net/npm/@yaireo/tagify"></script>
    <script src="https://cdn.jsdelivr.net/npm/@yaireo/tagify/dist/tagify.polyfills.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/@yaireo/tagify/dist/tagify.css" rel="stylesheet" type="text/css" />
    <link href="https://cdn.jsdelivr.net/npm/tom-select/dist/css/tom-select.css" rel="stylesheet" />
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
                    <ul class="font-medium flex flex-col p-4 md:p-0 mt-4 border md:-ml-16 border-gray-100 rounded-lg md:flex-row md:space-x-8 rtl:space-x-reverse md:mt-0 md:border-0">
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
                <?php if (isset($_SESSION['email']) == true) { ?>
                    <div class="hidden w-full md:block md:w-auto" id="navbar-default">
                        <ul class="font-medium flex flex-col p-4 md:p-0 mt-4 border border-gray-100 rounded-lg md:flex-row md:space-x-8 rtl:space-x-reverse md:mt-0 md:border-0">

                            <li>
                                <a class="block py-2 px-3 text-gray-900 rounded hover:bg-gray-100 md:hover:bg-transparent md:border-0 md:hover:text-blue-700 md:p-0 dark:text-white md:dark:hover:text-blue-500 dark:hover:bg-gray-700 dark:hover:text-white md:dark:hover:bg-transparent"><?= $_SESSION['email']; ?></a>
                            </li>

                        </ul>
                    </div>
                <?php } ?>
            </div>
        </nav>

    </header>
    <main class="min-h-[85vh] w-[90%] mb-5 mx-auto">
<?php include('alert.php');?>

        <?php include('profil_none.php'); ?>
        <?php include('profil_dash.php'); ?>
    </main>
    <!-- footer boostrap -->


    <?php include('footer.php'); ?>


    <script>
        // The DOM element you wish to replace with Tagify
        var input = document.querySelector('input[name=Mytags]');
        // initialize Tagify on the above input node reference
        new Tagify(input);
    </script>
    <script src="https://cdn.jsdelivr.net/npm/tom-select/dist/js/tom-select.complete.min.js"></script>
    <script>
        new TomSelect('#select-role', {
            maxItems: 10,
        });
    </script>
</body>

</html>