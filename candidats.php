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
            //chemin
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
                $errors[] = "This file extension is not allowed. Please upload a JPEG or PNG file";
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
                    $requestDone = $CV->insertCandidat(["inputNom" => $_POST["Nom1"], "inputPrenom" => $_POST["Prenom1"], "inputAge" => "$age", "inputDate" => $_POST["naissance1"], "inputAdresse" => $_POST["adresse"], "inputAdresse_1" => $_POST["adresse1"], "inputPostal" => $_POST["postal"], "inputVille" => $_POST["ville"], "inputPortable" => $_POST["portable1"], "inputFixe" => $_POST["fixe"], "inputMail" => $_POST["mail1"], "inputProfil" => "", "inputWeb" => "", "inputLink" => "", "inputVia" => "", "inputFace" => "", "inputCv" => $uploadPath]);

                    //recupere l'id MAX qui correspond au dernier ID donné
                    $ID = $CV->getId();
                    $requestDone = $CV->insertId(["monMail" => $_SESSION["email"], "inputId" => $ID[0][0]]);
                    //on va boucler avec le nombre de TAG au total que nous avons 
                    for ($i = 0; $i < count($_POST['tags_new']); $i++) {
                        //création requete "UPDATE tablename SET Competence_$v = :inputTag$i where Id = :monID" => modif de la ligne -- id de la copetence est egal a l'id du tag -- ou l'id de la table est egal à l'ID de l'input
                        //nous avons mis deux parametres dans la fonction ici $i / ["inputTag$i" => $_POST['tags_new'][$i], "monID" => $ID[0][0]] inputTag coorespond a ce qui a été entré dans l'encart nommé compétence (ex:Anglais) => le post correspond a ce que je recupere de l'encart nommé compétence donc les deux veulent dire la meme chose sauf que pour une question de sécurité je dois le nommer differement dans ma requete sql (:input)
                        //$i correspond au nombre de fois que je vais boucler c'est a dire à mon nombre de compétences que j'ai au total            
                        $requestInput = $CV->insertCompt($i, ["inputTag$i" => $_POST['tags_new'][$i], "monID" => $ID[0][0]]);

                        //monNomCompt correspond au nom que j'entre dans tags_New (encart competences) fetch (dans la fonction) revoie 1 ou 0, 1 correspond a quelque chose d'existant donc si le nom tapé dans l'encart existe deja dans la table
                        if ($CV->getCompetences(["monNomCompt" => $_POST['tags_new'][$i]]) > 0) {
                            //Alors je lance la requete insertTAG j'ajoute à ma table competence les nouveau nom de comptétence
                        } else {
                            $requestDone = $CV->insertTag(["inputNom" => $_POST['tags_new'][$i]]);
                            $CV->deco();
                        }
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
    $today = getdate();
    $annee = explode('-', $_POST["d1"]);
    $firstString = $annee[0];
    $age = ($today['year'] - $firstString);
    $result = $CV->gettout(["inputMail" => $_SESSION['email']]);
    $profil_Update = $CV->insertprofil1(["inputNom" => $_POST["n1"], "inputPrenom" => $_POST["p1"], "inputNaissance" => $_POST["d1"], "inputAge" => $age, "inputAdresse" => $_POST["ad1"], "inputAdresse1" => $_POST["add1"], "inputPostal" => $_POST["po1"], "inputVille" => $_POST["v1"], "inputPortable" => $_POST["t1"], "inputFixe" => $_POST["f1"], "inputMail" => $_POST["e1"], "inputProfil" => $_POST["pr1"], "inputWeb"=>$_POST["s1"], "inputLink"=>$_POST["s2"], "inputVid"=>$_POST["s3"], "inputFB"=>$_POST["s4"], "monId" => $result['profil_id']]);
}
// UPDATE CV
if (isset($_POST['upCV'])) {
    //chemin
    $uploadDirectory = "uploads/";

    $errors = []; // Store errors here

    $fileExtensionsAllowed = ['pdf']; // extension 

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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script type="module">
        // tag 
        import Tags from "https://cdn.jsdelivr.net/gh/lekoala/bootstrap5-tags@master/tags.js";
        Tags.init("select");
    </script>
    <script src="js/app.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script>
    <script src="https://kit.fontawesome.com/f751d235a8.js" crossorigin="anonymous"></script>
    <title>Document</title>
</head>

<body style="background: url('img/fond_candidats.webp');background-size:cover; background-repeat: no-repeat; background-position: center;">
    <header>
        <nav class="navbar navbar-expand-lg navbar-light" style="background-color: hsla(0, 0%, 94%, 80%);">
            <div class="container-fluid">
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarTogglerDemo03" aria-controls="navbarTogglerDemo03" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <a class="navbar-brand" href="index.php">Germa-karrière.com</a>
                <div class="collapse navbar-collapse" id="navbarTogglerDemo03">
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0 d-flex justify-content-between w-100">
                        <div class="d-flex flex-row">
                            <li class="nav-item">
                                <a class="nav-link active" aria-current="page" href="index.php">Accueil</a>
                            </li>
                            <!-- si je suis connécte a une session alors j'ai acces à recruteurs(tout depend du role)/candidats/deco-->
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
                        </div>
                        <?php if (isset($_SESSION['email']) == true) { ?>
                            <li class="nav-item">
                                <a class="nav-link"><?= $_SESSION['email']; ?></a>
                            </li>
                        <?php } ?>
                    </ul>
                </div>
            </div>
        </nav>
    </header>
    <main class="d-flex flex-column w-100 justify-content-center align-items-center bg-image mt-2" style="min-height: 85vh;">

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

        <!-- si je suis connecte à une session alors le mail de ma BDD correspond au mail de ma session-->
        <?php if (isset($_SESSION['email']) == true) {
            $result = $CV->gettout(["inputMail" => $_SESSION['email']]);
            // si dans ma BDD j'ai un ID à zero et donc non crée alors je creer un formulaire 
            if ($result["profil_id"] == 0) { ?>


                <form method="post" enctype="multipart/form-data" class="w-50">
                    <div class="p-4 shadow-4 rounded-3" style="background-color: hsla(0, 0%, 94%, 80%);">

                        <div class="mb-3">
                            <div class="d-flex md-flex-row gap-3 mb-3">
                                <input placeholder="Nom" type="text" name="Nom1" class="form-control" id="Nom" require>
                                <input placeholder="Prénom" type="text" name="Prenom1" class="form-control" id="Prenom" require>
                            </div>

                            <input placeholder="Email" type="email" name="mail1" class="form-control mb-3" id="Email1" require>

                            <div class="d-flex md-flex-row gap-3 mb-3">
                                <input placeholder="Tel Fixe" type="tel" name="fixe" class="form-control" id="fixe">
                                <input placeholder="Tel Portable" type="tel" name="portable1" class="form-control" id="portable" require>
                            </div>

                            <input placeholder="Date de Naissance" type="date" name="naissance1" class="form-control mb-3" id="naissance" require>

                            <div class="d-flex md-flex-row gap-3 mb-3">
                                <input placeholder="Adresse" type="text" name="adresse" class="form-control" id="adresse">
                                <input placeholder="Complément d'adresse" type="text" name="adresse1" class="form-control" id="adresse1">
                            </div>

                            <div class="d-flex md-flex-row gap-3 mb-3">
                                <input placeholder="Code Postal" type="text" name="postal" class="form-control" id="postal">
                                <input placeholder="Ville" type="text" name="ville" class="form-control" id="ville">
                            </div>

                            <input placeholder="CV" type="file" name="the_file" class="form-control mb-3" id="fileToUpload">

                            <div class="row mb-3 g-3">
                                <div class="col-md-4 w-100">

                                    <select placeholder="Compétences" class="form-select" id="validationTagsNew" name="tags_new[]" multiple data-allow-new="true">
                                        <!-- aller chercher les competence dans la BDD competence -->
                                        <?php foreach ($CV->getCompetence() as $row) { ?>
                                            <option value="<?php print $row['Nom']; ?>"><?php print $row['Nom']; ?></option>
                                        <?php } ?>
                                    </select>
                                    <div class="invalid-feedback">Please select a valid tag.</div>
                                    <small class="d-flex justify-content-center text-muted opacity-25">Séléctionner vos compétences ou ajoutez les.</small>
                                </div>
                            </div>
                        </div>
                        <div class="d-flex justify-content-center">
                            <button type="submit" class="btn btn-primary w-50" name="sauces">Créer</button>
                        </div>
                    </div>

                </form>
        <?php }
        }
        ?>
        <!-- si je suis connecte à une session -->
        <?php if (isset($_SESSION['email']) == true) {
            $result = $CV->gettout(["inputMail" => $_SESSION['email']]);
            // si j'ai un ID superieur à zero et donc existant alors j'affiche mon profil 
            if ($result["profil_id"] > 0) {
        ?>

                <section class="d-flex flex-row align-items-center justify-content-center m-auto p-4 shadow-4 rounded-3 mb-5 mt-2" style="background-color: hsla(0, 0%, 94%, 80%);">
                    <?php
                    $touslescandidats = $CV->getmonprofil(["inputMail" => $_SESSION["email"]]); ?>
                    <article> <!-- Afficher le profil -->
                        <form method="post" enctype="multipart/form-data">
                            <p>
                            <div class="input-group has-validation w-50">
                                <span class="input-group-text" id="inputGroupPrepend"><i class="fa-solid fa-user"></i></span>
                                <input type="text" class="form-control me-1" name="n1" id="" value="<?= strtoupper($touslescandidats["Nom"]) ?>">
                                <input type="text" class="form-control me-1 rounded" name="p1" id="" value=" <?= $touslescandidats["Prenom"] ?>">
                            </div>
                            </p>
                            <p>
                            <div class="input-group has-validation w-50">
                                <span class="input-group-text" id="inputGroupPrepend"><i class="fa-solid fa-cake-candles"></i></span>
                                <input type="text" class="form-control me-1" name="a1" id="" value="<?= $touslescandidats["Age"] ?>">
                            </div>
                            </p>

                            <p>
                            <div class="input-group has-validation w-50">
                                <span class="input-group-text" id="inputGroupPrepend"><i class="fa-solid fa-cake-candles"></i></span>
                                <input type="date" class="form-control me-1" name="d1" id="" value="<?= $touslescandidats["Date_naissance"] ?>">
                            </div>
                            </p>

                            <p>
                            <div class="input-group has-validation w-50">
                                <span class="input-group-text" id="inputGroupPrepend"><i class="fa-solid fa-location-dot"></i></span>
                                <input type="text" class="form-control me-1" name="ad1" id="" value="<?= $touslescandidats["Adresse"] ?>">
                                <input type="text" class="form-control me-1 rounded" name="add1" id="" value="<?= $touslescandidats["Adresse_1"] ?>">
                            </div>
                            </p>

                            <p>
                            <div class="input-group has-validation w-50">
                                <span class="input-group-text" id="inputGroupPrepend"><i class="fa-solid fa-location-dot"></i></span>
                                <input type="text" class="form-control me-1" name="po1" id="" value="<?= $touslescandidats["Code_postal"] ?>">
                                <input type="text" class="form-control me-1 rounded" name="v1" id="" value="<?= $touslescandidats["ville"] ?>">
                            </div>
                            </p>
                            <p>
                            <div class="input-group has-validation w-50">
                                <span class="input-group-text" id="inputGroupPrepend"><i class="fa-solid fa-phone"></i></span>
                                <input type="text" class="form-control me-1" name="t1" id="" value="<?= $touslescandidats["tel_portable"] ?>">
                                <input type="text" class="form-control me-1 rounded" name="f1" id="" value="<?= $touslescandidats["tel_fixe"] ?>">
                            </div>
                            </p>

                            <p>
                            <div class="input-group has-validation w-50">
                                <span class="input-group-text" id="inputGroupPrepend"><i class="fa-solid fa-envelope"></i></span>
                                <input type="text" class="form-control me-1" name="e1" id="" value="<?= $touslescandidats["Email"] ?>">
                            </div>
                            </p>

                            <p>
                            <div class="input-group has-validation w-50">
                                <span class="input-group-text" id="inputGroupPrepend"><i class="fa-solid fa-briefcase"></i></span>
                                <input type="text" class="form-control me-1" name="pr1" id="" value="<?= $touslescandidats["Profil"] ?>">
                            </div>
                            </p>


                            <p class="w-50"><span class="badge bg-info text-dark"><?= $touslescandidats["Competence_1"] ?></span>
                                <span class="badge bg-info text-dark"><?= $touslescandidats["Competence_2"] ?></span>
                                <span class="badge bg-info text-dark"><?= $touslescandidats["Competence_3"] ?></span>
                                <span class="badge bg-info text-dark"><?= $touslescandidats["Competence_4"] ?></span>
                                <span class="badge bg-info text-dark"><?= $touslescandidats["Competence_5"] ?></span>
                                <span class="badge bg-info text-dark"><?= $touslescandidats["Competence_6"] ?></span>
                                <span class="badge bg-info text-dark"><?= $touslescandidats["Competence_7"] ?></span>
                                <span class="badge bg-info text-dark"><?= $touslescandidats["Competence_8"] ?></span>
                                <span class="badge bg-info text-dark"><?= $touslescandidats["Competence_9"] ?></span>
                                <span class="badge bg-info text-dark"><?= $touslescandidats["Competence_10"] ?></span>
                            </p>
                            <p>
                            <div class="input-group has-validation w-50">
                                <span class="input-group-text" id="inputGroupPrepend"><i class="fa-solid fa-globe"></i></span>
                                <input type="text" class="form-control me-1" id="" name="s1" value="<?= $touslescandidats["Site_Web"] ?>">
                                <div class="d-flex align-items-center">
                                    <a href="<?= $touslescandidats["Site_Web"] ?>" data-bs-toggle="tooltip" data-bs-placement="top" title="Visiter le site web" target="_blank" class="fa-solid fa-right-long fa-2xl"></i></a>
                                </div>
                            </div>
                            </p>

                            <p>
                            <div class="input-group has-validation w-50">
                                <span class="input-group-text" id="inputGroupPrepend"><i class="fa-brands fa-linkedin"></i></span>
                                <input type="text" class="form-control me-1" id="" name="s2" value="<?= $touslescandidats["Profil_Linkedin"] ?>">
                                <div class="d-flex align-items-center">
                                    <a href="<?= $touslescandidats["Profil_Linkedin"] ?>" data-bs-toggle="tooltip" data-bs-placement="top" title="Visiter le site web" target="_blank" class="fa-solid fa-right-long fa-2xl"></i></a>
                                </div>
                            </div>
                            </p>

                            <p>
                            <div class="input-group has-validation w-50">
                                <span class="input-group-text" id="inputGroupPrepend"><i class="fa-brands fa-viadeo"></i></span>
                                <input type="text" class="form-control me-1" id="" name="s3" value="<?= $touslescandidats["Profil_Viadeo"] ?>">
                                <div class="d-flex align-items-center">
                                    <a href="<?= $touslescandidats["Profil_Viadeo"] ?>" data-bs-toggle="tooltip" data-bs-placement="top" title="Visiter le site web" target="_blank" class="fa-solid fa-right-long fa-2xl"></i></a>
                                </div>
                            </div>
                            </p>


                            <p>
                            <div class="input-group has-validation w-50">
                                <span class="input-group-text" id="inputGroupPrepend"><i class="fa-brands fa-facebook"></i></span>
                                <input type="text" class="form-control me-1" id="" name="s4" value="<?= $touslescandidats["Profil_facebook"] ?>">
                                <div class="d-flex align-items-center">
                                    <a href="<?= $touslescandidats["Profil_facebook"] ?>" data-bs-toggle="tooltip" data-bs-placement="top" title="Visiter le site web" target="_blank" class="fa-solid fa-right-long fa-2xl"></i></a>
                                </div>
                            </div>
                            </p>
                            <div class="d-flex justify-content-center">
                                <button type="submit" class="btn btn-primary w-50" name="sauces_profil">Mettre à jour mon profil</button>
                            </div>
                        </form>

                    </article>
                    <article>
                    <p class="w-50">
                        <form method="post" enctype="multipart/form-data">
                                <input placeholder="CV" type="file" name="monCV" class="form-control mb-3" id="monCV">
                                <button type="submit" class="btn btn-primary w-100" name="upCV">Mettre à jour mon CV</button>
                   
                        </form>
                        </p>
                        <p><embed src=<?= $touslescandidats["CV"] ?> width=800 height=600 type='application/pdf' />
                        </p>
                    </article>
                </section>
        <?php }
        }
        $CV->deco(); ?>
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
    <script>
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        })
    </script>
</body>

</html>