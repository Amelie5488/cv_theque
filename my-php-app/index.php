<?php

//Démarre une nouvelle session 
session_start();
require_once('php/config/config.php');
// connection a la bbd
$CV = new CV();
$CV->connexion();

// Si la session connectée a le role 0 alors on renvoi sur la page candidat 
if (isset($_SESSION['email']) == true && isset($_SESSION['role']) == 0) {
    header('location: candidats.php');

// Si la session connectée a le role 1 alors on renvoi sur la page recruteur     
} elseif (isset($_SESSION['email']) == true && isset($_SESSION['role']) == 1) {
    header('location: recruteurs.php');
}
// variable pour les alertes
$error = "";
$succes = "";

// se connecter 
if (isset($_POST["connect"])) {
    // si les champs pour se connecter (mail et mot de passe) sont vide alors il y a une alerte 
    if (empty($_POST["mailconnect"] && $_POST["passwordconnect"])) {
        $error =  "Certains champs sont vide";
    } else {

        // sinon la fonction gettout se connecte a la totalité de la BDD afin de comparer l'adresse mail deja enregistré dans la BDD et ce qui est inscrit dans le post mailconnect
        $requestDone = $CV->gettout(["inputMail" => $_POST["mailconnect"]]);
        // si ma requete renvoie 1 c'est a dire que l'adresse mail existe 
        // le retour en fetch renvoie les reponse 0 ou 1 donc inexistant ou existant
        if ($requestDone > 0) {
            // si le mot de passe dans le post est identique au mot de passe qui a été hash dans la BDD
            if (password_verify($_POST["passwordconnect"], $requestDone["Password"])) {
                //le mail de la session est identique au mail au mail de la BDD
                $_SESSION["email"] = $requestDone["Mail"];
                //le role de la session est identique au role de la BDD
                $_SESSION["role"] = $requestDone["role"];

        
                // Si la session connecté a le role 0
                if (isset($_SESSION['email']) == true && isset($_SESSION['role']) == 0) {
                    //alors on renvoi sur la page candidats
                    header('location: candidats.php');
                //Si la session connecté a le role 1
                } elseif (isset($_SESSION['email']) == true && isset($_SESSION['role']) == 1) {
                    // alors on renvoi sur la page recruteur 
                    header('location: recruteurs.php');
                }
                $CV->deco();
            }
        } else {
            //sinon message d'erreur 
            $error = "les comptes sont pas bon kevin";
        }
    }
}
//creer un compte 
if (isset($_POST["creation"])) {
    // si les champs mail et mot de passe sont vide 
    if (empty($_POST["mailcreation"] && $_POST["passwordcreation"])) {
        //Alors un msg d'erreur s'affiche 
        $error =  "Certains champs sont vide";
    } else {
        //securise le mot de passe et le hash dans la base de données 
        $email = htmlentities(stripslashes($_POST["mailcreation"]));
        $password = htmlentities(stripslashes($_POST["passwordcreation"]));
        $passHash = password_hash($password, PASSWORD_ARGON2ID);
        //la fonction get table prend tout de la table table name 
        $result = $CV->getTable(["inputEmail" => $email]);
        //la fonction gettout prend tout mais de la table compte 
        $compte_OK = $CV->gettout(["inputMail" => $email]);

        // reCAPTCHA validation
        // Si le Captcha est bien selectionné et si il est different de vide 
        if (isset($_POST['g-recaptcha-response']) && !empty($_POST['g-recaptcha-response'])) {

            // Google secret API
            $secretAPIkey = '6Lcu3ycpAAAAAGhwMQyuH-5SSmJaXqlTMRX4BVgn';

            // reCAPTCHA response verification
            $verifyResponse = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret=' . $secretAPIkey . '&response=' . $_POST['g-recaptcha-response']);

            // Decode JSON data
            // renvoi un fichier Json
            $response = json_decode($verifyResponse);
            //Si c'est un succes
            if ($response->success) {
                if ($compte_OK < 1) {
                    //Si le mot de passe est bon 
                    if ($_POST["passwordcreation"] == $_POST["passwordconfirm"]) {
                        //Je lance la fonction insert user (création de compte)
                        if ($result > 0) {
                            //Si le canndidat a deja un profil de créer alors on reprend l'id de son profil 
                            $requestDone = $CV->insertUser(["inputemail" => $email, "inputpassword" => $passHash, "inputId" => $result['Id'], "roleDefault"=>"0"]);
                            $succes = "Création de votre compte validé";
                            $CV->deco();
                        } else {
                            //Sinon on lui creer un compte avec l'ID 0 il devra creer son formulaire pour avoir son profil 
                            $requestDone = $CV->insertUser(["inputemail" => $email, "inputpassword" => $passHash, "inputId" => '0', "roleDefault"=>"0"]);
                            $succes = "Création de votre compte validé";
                            $CV->deco();
                        }
                    } else {
                        $error = "Les comptes sont pas bon kévin";
                    }
                } else {
                    $error = "Le mail est déjà utilisé";
                }
            } else {
                $error = "Captcha non valide";
            }
        } else {
            $error = "Captcha non réalisé";
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
    <script src="https://kit.fontawesome.com/f751d235a8.js" crossorigin="anonymous"></script>
    <title>Germa-karrière.com</title>
</head>


<body style="background: url('img/fond.jpg');background-size:cover; background-repeat: no-repeat; background-position: center;">
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
                        <!-- si il est connecté à une session et si son role est =1 (acces recruteur) alors j'affiche-->
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
                            <li class="flex items-center">
                                <a class="block py-2 px-3 text-gray-900 rounded hover:bg-gray-100 md:hover:bg-transparent md:border-0 md:hover:text-blue-700 md:p-0 dark:text-white md:dark:hover:text-blue-500 dark:hover:bg-gray-700 dark:hover:text-white md:dark:hover:bg-transparent"><?= $_SESSION['email']; ?></a>
                            </li>
                        </ul>
                    </div>
                <?php } ?>
            </div>
        </nav>
    </header>
    <main class="min-h-[80vh] flex flex-col items-center justify-center w-full">
        <section class="w-[65%]">
            <?php include('alert.php'); ?>
        </section>
        <?php if (isset($_SESSION['email']) == false) { ?>
            <section class="w-full">
                <div class="py-8 px-4 mx-auto max-w-screen-xl lg:py-16 grid lg:grid-cols-2 gap-8 lg:gap-16">
                    <div class="flex flex-col justify-center">
                        <h1 class="mb-4 text-4xl font-extrabold tracking-tight leading-none text-white md:text-5xl lg:text-6xl dark:text-white">Germa-Karrière</h1>
                        <p class="mb-6 text-lg font-normal text-white lg:text-xl dark:text-gray-400">Avec Germa-karrière, gère ta carrière !</p>
                    </div>
                    <div>
                        <div class="w-full lg:max-w-xl p-6 space-y-8 sm:p-8 bg-white rounded-lg shadow-xl dark:bg-gray-800">
                            <h2 class="text-2xl font-bold text-gray-900 dark:text-white">
                                Se connecter à son espace
                            </h2>
                            <form class="mt-8 space-y-6" method="post">
                                <div>
                                    <input type="email" name="mailconnect" id="email" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="name@company.com" required>
                                </div>
                                <div>
                                    <input type="password" name="passwordconnect" id="password" placeholder="••••••••" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" required>
                                </div>
                                <button type="submit" name="connect" class="w-full px-5 py-3 text-base font-medium text-center text-white bg-blue-700 rounded-lg hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 sm:w-auto dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Se connecter</button>
                                <div class="text-sm font-medium text-gray-900 dark:text-white">
                                    Pas encore inscrit ? <a class="text-blue-600 hover:underline dark:text-blue-500" data-modal-target="authentication-modal" data-modal-toggle="authentication-modal">Créer un compte</a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </section>

            <section>
                <!-- Main modal -->
                <div id="authentication-modal" tabindex="-1" aria-hidden="true" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
                    <div class="relative p-4 w-full max-w-md max-h-full">
                        <!-- Modal content -->
                        <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                            <!-- Modal header -->
                            <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600">
                                <h3 class="text-xl font-semibold text-gray-900 dark:text-white">
                                    Créer votre espace
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
                                <form class="mt-8 space-y-6" method="post">
                                    <div>
                                        <input type="email" name="mailcreation" id="email" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="name@company.com" required>
                                    </div>
                                    <div>
                                        <input type="password" name="passwordcreation" id="password" placeholder="••••••••" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" required>
                                    </div>
                                    <div>
                                        <input type="password" name="passwordconfirm" id="password" placeholder="••••••••" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" required>
                                    </div>
                                    <div class="g-recaptcha mb-3" data-sitekey="6Lcu3ycpAAAAANZEg1MkZYaFZNDAxHVejwtFsW_a"></div>
                                    <button type="submit" name="creation" class="w-full px-5 py-3 text-base font-medium text-center text-white bg-blue-700 rounded-lg hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 sm:w-auto dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Créer un compte</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

            </section>
        <?php } ?>
    </main>

    <?php include('footer.php'); ?>

    <!-- <script src="js/toggle.js"></script> -->
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    <script src="js/script.js"></script>
</body>

</html>


<!-- Je le garde car c'est moi qui l'est fait mais Damien a pas voulu le garder !! 
        Mais, vu qu'on se connect directement via l'index AUCUNE PUTAIN d'utilitée
            <section class="d-flex flex-row justify-content-around w-100">
            <a class="d-flex flex-column align-items-center" href="recruteurs.php"><img class="" src="img/recruteur.png">
                <h2 class="pt-3">Espace recruteurs</h2>
            </a>
            <a class="d-flex flex-column align-items-center" href="candidats.php"><img class="" src="img/candidat.jpg">
                <h2 class="pt-3">Espace candidats</h2>
            </a>
        </section> -->