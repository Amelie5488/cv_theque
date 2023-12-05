<?php
session_start();
require_once('php/config/config.php');

$CV = new CV();
$CV->connexion();
if (isset($_SESSION['email']) == true) {
    $result = $CV->gettout(["inputMail" => $_SESSION['email']]);
}

if (isset($_POST["sauce"])) {
    if (empty($_POST["mail"] && $_POST["password"])) {
        echo "vide";
    } else {
        //securise le mot de passe et le hash dans la base de données 
        $email = htmlentities(stripslashes($_POST["mail"]));
        $password = htmlentities(stripslashes($_POST["password"]));
        $passHash = password_hash($password, PASSWORD_ARGON2ID);
        $result = $CV->getTable(["inputEmail" => $email]);
        if ($result > 0) {
            $requestDone = $CV->insertUser(["inputemail" => $email, "inputpassword" => $passHash, "inputId" => $result['Id']]);
        } else {
            $requestDone = $CV->insertUser(["inputemail" => $email, "inputpassword" => $passHash, "inputId" => '0']);
        }
    }
}

if (isset($_POST["sauces"])) {
    //si les champ nom/prenom/mail/naissance/portable sont vide (empty)
    if (empty($_POST["Nom1"] && $_POST["Prenom1"] && $_POST["mail1"] && $_POST["naissance1"] && $_POST["portable1"])) {
        //alors ecrire
        echo "vide";
        //sinon
    } else {
        //un minimum de 5 competences a entrer et un maximum de 10 
        if (count($_POST['tags_new']) >= 5 && count($_POST['tags_new']) <= 10) {
            $currentDirectory = getcwd();
            $uploadDirectory = "uploads/";

            $errors = []; // Store errors here

            $fileExtensionsAllowed = ['pdf', 'docx']; // These will be the only file extensions allowed 

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
                $didUpload = move_uploaded_file($fileTmpName, $uploadPath);

                if ($didUpload) { 
                    $today = getdate();
                    $annee = explode('-', $_POST["naissance1"]);
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
                        }
                    }
                } else {
                    echo "An error occurred. Please contact the administrator.";
                }
            } else {
                foreach ($errors as $error) {
                    echo $error . "These are the errors" . "\n";
                }
            }
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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <!-- <link rel="stylesheet" href="css/style.css"> -->
    <script type="module">
        import Tags from "https://cdn.jsdelivr.net/gh/lekoala/bootstrap5-tags@master/tags.js";
        Tags.init("select");
    </script>
    <script src="js/app.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script>
    <title>Document</title>
</head>

<body>
    <header>
        <nav class="navbar navbar-expand-lg navbar-light bg-light">
            <div class="container-fluid">
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarTogglerDemo03" aria-controls="navbarTogglerDemo03" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <a class="navbar-brand" href="#">Navbar</a>
                <div class="collapse navbar-collapse" id="navbarTogglerDemo03">
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                        <li class="nav-item">
                            <a class="nav-link active" aria-current="page" href="#">Home</a>
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

    <?php if (isset($_SESSION['email']) == false) { ?>
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal">
            Créer un compte
        </button>

        <!-- Modal creation de compte-->
        <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="exampleModalLabel">Créer un compte</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form method="post">
                            <div class="mb-3">
                                <label for="" class="form-label">Email address</label>
                                <input type="email" name="mail" class="form-control" id="Email">
                                <label for="" class="form-label">Password</label>
                                <input type="password" name="password" class="form-control" id="password">
                                <div class="modal-footer">
                                    <button type="submit" class="btn btn-primary" name="sauce">Créer</button>
                                </div>
                            </div>
                    </div>
                </div>
            </div>
        </div>
        </form>
    <?php } ?>
    <?php if (isset($_SESSION['email']) == true) {
        $result = $CV->gettout(["inputMail" => $_SESSION['email']]);
        if ($result["profil_id"] == 0) { ?>
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal2">
                Créer un formulaire
            </button>

            <!-- Modal creation de formulaire-->
            <div class="modal fade" id="exampleModal2" tabindex="-1" aria-labelledby="exampleModalLabel2" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h1 class="modal-title fs-5 w-100 text-center" id="exampleModalLabel2">Création de votre profil</h1>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <form method="post" enctype="multipart/form-data">
                            <div class="modal-body">

                                <div class="mb-3">
                                    <div class="d-flex md-flex-row gap-3 mb-3">
                                        <input placeholder="Nom" type="text" name="Nom1" class="form-control" id="Nom">
                                        <input placeholder="Prénom" type="text" name="Prenom1" class="form-control" id="Prenom">
                                    </div>

                                    <input placeholder="Email" type="email" name="mail1" class="form-control mb-3" id="Email1">

                                    <div class="d-flex md-flex-row gap-3 mb-3">
                                        <input placeholder="Tel Fixe" type="tel" name="fixe" class="form-control" id="fixe">
                                        <input placeholder="Tel Portable" type="tel" name="portable1" class="form-control" id="portable">
                                    </div>

                                    <input placeholder="Date de Naissance" type="date" name="naissance1" class="form-control mb-3" id="naissance">

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
                                                <?php foreach ($CV->getCompetence() as $row) { ?>
                                                    <option value="<?php print $row['Nom']; ?>"><?php print $row['Nom']; ?></option>
                                                <?php } ?>
                                            </select>
                                            <div class="invalid-feedback">Please select a valid tag.</div>
                                            <small class="d-flex justify-content-center text-muted opacity-25">Séléctionner vos compétences ou ajoutez les.</small>
                                        </div>
                                    </div>


                                </div>

                            </div>
                            <div class="modal-footer">
                                <button type="submit" class="btn btn-primary" name="sauces">Créer</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

    <?php }
    } ?>

    <?php if (isset($_SESSION['email']) == true) {
        $result = $CV->gettout(["inputMail" => $_SESSION['email']]);
        if ($result["profil_id"] > 0) {
    ?>

            <section class="d-flex flex-row align-items-center justify-content-center m-auto">
                <?php
                $touslescandidats = $CV->getmonprofil(["inputMail" => $_SESSION["email"]]); ?>


                <!--<form method="post">
                                <button type="submit" class="btn btn-primary" name="Modif<?= $touslescandidats["Id"]; ?>"> Modifier</button>
                                <button type="submit" class="btn btn-danger" name="Supprimer<?= $touslescandidats["Id"]; ?>"> Supprimer</button>
                            </form>-->
                <article>
                    <p>Nom : <?= strtoupper($touslescandidats["Nom"]) ?></p>
                    <p>Prénom <?= $touslescandidats["Prenom"] ?></p>
                    <p>Âge : <?= $touslescandidats["Age"] ?></p>
                    <p>Date de naissance : <?= $touslescandidats["Date_naissance"] ?></p>
                    <p>Adresse : <?= $touslescandidats["Adresse"] ?> <?= $touslescandidats["Adresse_1"] ?></p>
                    <p>Code postal : <?= $touslescandidats["Code_postal"] ?></p>
                    <p>Ville : <?= $touslescandidats["ville"] ?></p>
                    <p>Téléphone portable : <?= $touslescandidats["tel_portable"] ?></p>
                    <p>Téléphone fixe : <?= $touslescandidats["tel_fixe"] ?></p>
                    <p>Email : <?= $touslescandidats["Email"] ?></p>
                    <p>Domaine : <?= $touslescandidats["Profil"] ?></p>


                    <p class="w-75">Compétences : <span class="badge bg-info text-dark"><?= $touslescandidats["Competence_1"] ?></span>
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

                    <p><a href="<?= $touslescandidats["Site_Web"] ?>">Site web personnel</a></p>
                    <p><a href="<?= $touslescandidats["Profil_Linkedin"] ?>">Profil Linkedin</a></p>
                    <p><a href="<?= $touslescandidats["Profil_Viadeo"] ?>">Profil Viadéo</a></p>
                    <p><a href="<?= $touslescandidats["Profil_facebook"] ?>">Profil facebook</a></p>
                </article>
                <article>
                    <p><embed src=<?= $touslescandidats["CV"] ?> width=800 height=800 type='application/pdf' />
                    </p>
                </article>
            </section>
    <?php }
    } ?>
</body>

</html>