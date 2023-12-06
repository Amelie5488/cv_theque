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
                $CV-> deco();
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
        if (isset($_POST['g-recaptcha-response']) && !empty($_POST['g-recaptcha-response'])) {

            // Google secret API
            $secretAPIkey = '6Lcu3ycpAAAAAGhwMQyuH-5SSmJaXqlTMRX4BVgn';

            // reCAPTCHA response verification
            $verifyResponse = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret=' . $secretAPIkey . '&response=' . $_POST['g-recaptcha-response']);

            // Decode JSON data
            $response = json_decode($verifyResponse);
            if ($response->success) {
                if ($compte_OK < 1) {
                    if ($_POST["passwordcreation"] == $_POST["passwordconfirm"]) {
                        if ($result > 0) {
                            $requestDone = $CV->insertUser(["inputemail" => $email, "inputpassword" => $passHash, "inputId" => $result['Id']]);
                            $succes = "Création de votre compte validé";
                            $CV-> deco();
                        } else {
                            $requestDone = $CV->insertUser(["inputemail" => $email, "inputpassword" => $passHash, "inputId" => '0']);
                            $succes = "Création de votre compte validé";
                            $CV-> deco();
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
    <link rel="stylesheet" href="css/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script>
    <title>Germa-karrière.com</title>
</head>


<body style="background: url('img/fond.jpg');background-size:cover; background-repeat: no-repeat; background-position: center;">
    <header>
        <!--navbar -->
        <nav class="navbar navbar-expand-lg navbar-light" style="background-color: hsla(0, 0%, 94%, 80%);">
            <div class="container-fluid">
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarTogglerDemo03" aria-controls="navbarTogglerDemo03" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <a class="navbar-brand" href="index.php">Germa-karrière.com</a>
                <div class="collapse navbar-collapse" id="navbarTogglerDemo03">
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                        <li class="nav-item">
                            <a class="nav-link active" aria-current="page" href="index.php">Accueil</a>
                        </li>
                        <!-- si je suis connecté à une session alors je peux avoir acces au menu recruteurs(tout depend du role)/candidats/deco-->
                        <?php if (isset($_SESSION['email']) == true) { ?>
                            <li class="nav-item">
                                <a class="nav-link" href="recruteurs.php">Recruteurs</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="candidats.php">Candidats</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="deco.php">Déco</a>
                            </li>
                        <?php } ?>
                    </ul>
                </div>
            </div>
        </nav>
    </header>
    <main class="d-flex flex-row align-items-center w-100 bg-image">
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

        <section class="d-flex flex-column flex-md-row justify-content-around align-items-center w-75 m-auto">
            <!-- si je ne suis pas connecte a une session alors j'ai acces au champs de connection -->                
            <?php if (isset($_SESSION['email']) == false) { ?>
                <div class="p-4 shadow-4 rounded-3 w-50" id="login" style="background-color: hsla(0, 0%, 94%, 80%);">

                <!-- code boostrap pour les icones des alertes -->
                    <svg xmlns="http://www.w3.org/2000/svg" style="display: none;">
                        <symbol id="check-circle-fill" fill="currentColor" viewBox="0 0 16 16">
                            <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z" />
                        </symbol>
                        <symbol id="info-fill" fill="currentColor" viewBox="0 0 16 16">
                            <path d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16zm.93-9.412-1 4.705c-.07.34.029.533.304.533.194 0 .487-.07.686-.246l-.088.416c-.287.346-.92.598-1.465.598-.703 0-1.002-.422-.808-1.319l.738-3.468c.064-.293.006-.399-.287-.47l-.451-.081.082-.381 2.29-.287zM8 5.5a1 1 0 1 1 0-2 1 1 0 0 1 0 2z" />
                        </symbol>
                        <symbol id="exclamation-triangle-fill" fill="currentColor" viewBox="0 0 16 16">
                            <path d="M8.982 1.566a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767L8.982 1.566zM8 5c.535 0 .954.462.9.995l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995A.905.905 0 0 1 8 5zm.002 6a1 1 0 1 1 0 2 1 1 0 0 1 0-2z" />
                        </symbol>
                    </svg>

                    <!-- si il y a une erreur-->
                    <?php if ($error) { ?>
                        <!-- Alors msg d'alerte -->
                        <div class="alert alert-danger d-flex align-items-center" role="alert">
                            <svg class="bi flex-shrink-0 me-2" width="24" height="24" role="img" aria-label="Danger:">
                                <use xlink:href="#exclamation-triangle-fill" />
                            </svg>
                            <div>
                                <?= $error ?>
                            </div>
                        </div>
                    <?php } ?>
                    <!-- si il y a une erreur-->
                    <?php if ($succes) { ?>
                        <!-- Alors msg d'erreur -->
                        <div class="alert alert-success d-flex align-items-center" role="alert">
                            <svg class="bi flex-shrink-0 me-2" width="24" height="24" role="img" aria-label="Success:">
                                <use xlink:href="#check-circle-fill" />
                            </svg>
                            <div>
                                <?= $succes ?>
                            </div>
                        </div>
                    <?php } ?>

                    <!-- form pour se connecter -->            
                    <h2 class="mb-4">Accéder à mon espace personnel</h2>
                    <form method="post" class="w-100">
                        <div class="mb-3">
                            <label for="" class="form-label">Email address</label>
                            <input type="email" name="mailconnect" class="form-control mb-4" id="Email">
                            <label for="" class="form-label">Password</label>
                            <input type="password" name="passwordconnect" class="form-control mb-4" id="password">
                            <button type="submit" class="btn btn-primary w-25" name="connect">Se connecter</button>
                        </div>
                    </form>

                    <!-- form pour se creer un compte -->
                    <details>
                        <summary class="mb-4 border border-primary rounded bg-primary text-light w-25 text-center" style="list-style: none; padding : 1%;">Créer mon compte</summary>
                        <form method="post" class="w-100">
                            <div class="mb-3">
                                <label for="" class="form-label">Email address</label>
                                <input type="email" name="mailcreation" class="form-control mb-4" id="Email1" require>
                                <label for="" class="form-label">Password</label>
                                <input type="password" name="passwordcreation" class="form-control mb-4" id="password1" require>
                                <label for="" class="form-label">Confirmer votre mot de passe </label>
                                <input type="password" name="passwordconfirm" class="form-control mb-4" id="password2" require>
                                <div class="g-recaptcha mb-3" data-sitekey="6Lcu3ycpAAAAANZEg1MkZYaFZNDAxHVejwtFsW_a"></div>
                                <button type="submit" class="btn btn-primary" name="creation">Créer mon compte</button>
                            </div>
                        </form>
                    </details>
                </div>
            <?php } ?>
        </section>
    </main>

    <!-- footer boostrap -->
    <footer class="d-flex flex-wrap justify-content-between align-items-center mb-0 py-3 my-1 border-top" style="background-color: hsla(0, 0%, 94%, 80%);">
        <div class="col-md-4 d-flex align-items-center my-3">
            <a href="/" class="mb-3 me-2 mb-md-0 text-muted text-decoration-none lh-1">
                <svg class="bi" width="30" height="24">
                    <use xlink:href="#bootstrap" />
                </svg>
            </a>
            <span class="mb-3 mb-md-0 text-muted">&copy; 2022 Germa-karrière.com</span>
        </div>

        <ul class="nav col-md-4 justify-content-end list-unstyled d-flex">
            <li class="ms-3"><a class="text-muted" href="#"><svg class="bi" width="24" height="24">
                        <use xlink:href="#twitter" />
                    </svg></a></li>
            <li class="ms-3"><a class="text-muted" href="#"><svg class="bi" width="24" height="24">
                        <use xlink:href="#instagram" />
                    </svg></a></li>
            <li class="ms-3"><a class="text-muted" href="#"><svg class="bi" width="24" height="24">
                        <use xlink:href="#facebook" />
                    </svg></a></li>
        </ul>
    </footer>

    <!-- <script src="js/toggle.js"></script> -->
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
</body>

</html>