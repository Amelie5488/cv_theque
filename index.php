<?php
session_start();
require_once('php/config/config.php');

$CV = new CV();
$CV->connexion();

if (isset($_POST["connect"])) {
    if (empty($_POST["mailconnect"] && $_POST["passwordconnect"])) {
        echo "vide";
    } else {

        $requestDone = $CV->gettout(["inputMail" => $_POST["mailconnect"]]);
        if ($requestDone > 0) {
            if (password_verify($_POST["passwordconnect"], $requestDone["Password"])) {
                $_SESSION["email"] = $requestDone["Mail"];
                $_SESSION["role"] = $requestDone["role"];
            }
        } else {
            echo "les comptes sont pas bon kevin";
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
    <title>Document</title>
</head>

<body style="background: url('img/fond.jpg');background-size:cover; background-repeat: no-repeat; background-position: center;">
    <header>
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
        <!-- <section class="d-flex flex-row justify-content-around w-100">
            <a class="d-flex flex-column align-items-center" href="recruteurs.php"><img class="" src="img/recruteur.png">
                <h2 class="pt-3">Espace recruteurs</h2>
            </a>
            <a class="d-flex flex-column align-items-center" href="candidats.php"><img class="" src="img/candidat.jpg">
                <h2 class="pt-3">Espace candidats</h2>
            </a>
        </section> -->
        <section class="d-flex flex-column flex-md-row justify-content-around align-items-center w-75 m-auto">
            <?php if (isset($_SESSION['email']) == false) { ?>

                <!-- <div class="form-check form-switch" id="toggle">
                    <input class="form-check-input" type="checkbox" id="flexSwitchCheckDefault">
                </div> -->

                <!-- Modal se connecter-->
                <!-- Jumbotron -->
                <div class="p-4 shadow-4 rounded-3 w-50" id="login" style="background-color: hsla(0, 0%, 94%, 80%);">
                    <h2 class="mb-4">Accéder à mon espace personnel</h2>
                    <form method="post" class="w-100">
                        <div class="mb-3">
                            <label for="" class="form-label">Email address</label>
                            <input type="email" name="mailconnect" class="form-control mb-4" id="Email1">
                            <label for="" class="form-label">Password</label>
                            <input type="password" name="passwordconnect" class="form-control mb-4" id="password1">
                            <button type="submit" class="btn btn-primary" name="connect">Se connecter</button>
                        </div>
                    </form>

                    <h2 class="mb-4">Incrivez-vous</h2>
                    <form method="post" class="w-100">
                        <div class="mb-3">
                            <label for="" class="form-label">Email address</label>
                            <input type="email" name="mailconnect" class="form-control mb-4" id="Email1">
                            <label for="" class="form-label">Password</label>
                            <input type="password" name="passwordconnect" class="form-control mb-4" id="password1">
                            <button type="submit" class="btn btn-primary" name="connect">Se connecter</button>
                        </div>
                    </form>
                </div>
            <?php } ?>
        </section>
    </main>
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
</body>

</html>