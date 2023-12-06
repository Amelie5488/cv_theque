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
            $requestDone = $CV->insertCandidat(["inputNom" => $_POST["Nom"], "inputPrenom" => $_POST["Prenom"], "inputAge" => "$age", "inputDate" => $_POST["naissance"], "inputAdresse" => $_POST["adresse"], "inputAdresse_1" => $_POST["adresse1"], "inputPostal" => $_POST["postal"], "inputVille" => $_POST["ville"], "inputPortable" => $_POST["portable"], "inputFixe" => $_POST["fixe"], "inputMail" => $_POST["mail"], "inputProfil" => "", "inputWeb" => "", "inputLink" => "", "inputVia" => "", "inputFace" => ""]);

            //recupere l'id MAX qui correspond au dernier ID donné
            $ID = $CV->getId();

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
    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <script src="//cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js" integrity="sha384-BBtl+eGJRgqQAUMxJ7pMwbEyER4l1g+O15P+16Ep7Q9Q+zqX6gSbd85u4mG4QzX+" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="//cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css">

    <script type="module">
        //TAG
        import Tags from "https://cdn.jsdelivr.net/gh/lekoala/bootstrap5-tags@master/tags.js";
        Tags.init("select");
    </script>
    <script src="js/app.js"></script>
    <title>CV Thèque</title>
</head>

<body>
    <header>
        <nav class="navbar navbar-expand-lg navbar-light" style="background-color: hsla(0, 0%, 94%, 80%);">
            <div class="container-fluid">
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarTogglerDemo03" aria-controls="navbarTogglerDemo03" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <a class="navbar-brand" href="#">Navbar</a>
                <div class="collapse navbar-collapse" id="navbarTogglerDemo03">
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0 d-flex flex-row justify-content-between w-100">
                        <section class="d-flex flex-row ">
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
                        </section>
                        <li class="nav-item">
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal2">
                                Créer nouveau Candidat
                            </button>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
    </header>

    <!-- Modal formulaire creation de candidat-->
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
                            </div>
                            <div class="modal-footer">
                                <button type="submit" class="btn btn-primary" name="sauces">Créer</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

    <section class="m-auto" style="width : 90%">
    <!-- tableau pour afficher tous les candidats -->
        <table id="myTable" class="table table-hover">
            <thead class="table-dark">
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
                <th>Competence_1</th>
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
                        $profil_Update = $CV->insertprofil(["inputNom" => $_POST["Nom_" . $row['Id']], "inputPrenom" => $_POST["Prenom_" . $row['Id']], "inputNaissance" => $_POST["naissance_" . $row['Id']], "inputAge" => $age, "inputAdresse" => $_POST["adresse_" . $row['Id']], "inputAdresse1" => $_POST["adresse1_" . $row['Id']], "inputPostal" => $_POST["postal_" . $row['Id']], "inputVille" => $_POST["ville_" . $row['Id']], "inputPortable" => $_POST["portable_" . $row['Id']], "inputFixe" => $_POST["fixe_" . $row['Id']], "inputMail" => $_POST["Email_" . $row['Id']],"inputProfil"=>"", "monId" => $row['Id']]);
                    }
                }
                foreach ($touslescandidats as $row) {

                ?>

                    <tr>
                        <td>
                            <button type="button" class="btn btn-primary w-100" name="Modif <?= $row["Id"]; ?>" data-bs-target="#exampleModal_<?= $row['Id'] ?>" data-bs-toggle="modal"> Modifier</button>
                            <form method="post">
                                <button type="submit" class="btn btn-danger w-100" name="Supprimer<?= $row["Id"]; ?>"> Supprimer</button>
                            </form>
                        </td>
                        <td><?= strtoupper($row["Nom"]) ?></td>
                        <td><?= $row["Prenom"] ?></td>
                        <td><?= $row["Age"] ?></td>
                        <td><?= $row["Date_naissance"] ?></td>
                        <td><?= $row["Adresse"] ?> <?= $row["Adresse_1"] ?></td>
                        <td><?= $row["Code_postal"] ?></td>
                        <td><?= $row["ville"] ?></td>
                        <td><?= $row["tel_portable"] ?></td>
                        <td><?= $row["tel_fixe"] ?></td>
                        <td><?= $row["Email"] ?></td>
                        <td><?= $row["Profil"] ?></td>
                        <td>
                            <span class="badge bg-info text-dark"><?= $row["Competence_1"] ?></span>
                            <span class="badge bg-info text-dark"><?= $row["Competence_2"] ?></span>
                            <span class="badge bg-info text-dark"><?= $row["Competence_3"] ?></span>
                            <span class="badge bg-info text-dark"><?= $row["Competence_4"] ?></span>
                            <span class="badge bg-info text-dark"><?= $row["Competence_5"] ?></span>
                            <span class="badge bg-info text-dark"><?= $row["Competence_6"] ?></span>
                            <span class="badge bg-info text-dark"><?= $row["Competence_7"] ?></span>
                            <span class="badge bg-info text-dark"><?= $row["Competence_8"] ?></span>
                            <span class="badge bg-info text-dark"><?= $row["Competence_9"] ?></span>
                            <span class="badge bg-info text-dark"><?= $row["Competence_10"] ?></span>

                        </td>
                        <td><?= $row["Site_Web"] ?></td>
                        <td><?= $row["Profil_Linkedin"] ?></td>
                        <td><?= $row["Profil_Viadeo"] ?></td>
                        <td><?= $row["Profil_facebook"] ?></td>
                    </tr>
                    <!-- Modal pour modifier chaque candidiat -->
                    <div class="modal fade" id="exampleModal_<?= $row['Id'] ?>" tabindex="-1" aria-labelledby="exampleModalLabel_<?= $row['Id'] ?>" aria-hidden="true">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h1 class="modal-title fs-5 w-100 text-center" id="exampleModalLabel_<?= $row['Id'] ?>">Création de votre profil</h1>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <form method="post" enctype="multipart/form-data">
                                    <div class="modal-body">

                                        <div class="mb-3">
                                            <div class="d-flex md-flex-row gap-3 mb-3">
                                                <input placeholder="Nom" type="text" name="Nom_<?= $row['Id'] ?>" class="form-control" id="Nom_<?= $row['Id'] ?>" value="<?= $row['Nom'] ?>">
                                                <input placeholder="Prénom" type="text" name="Prenom_<?= $row['Id'] ?>" class="form-control" id="Prenom_<?= $row['Id'] ?>" value="<?= $row['Prenom'] ?>">
                                            </div>

                                            <input placeholder="Email" type="email" name="Email_<?= $row['Id'] ?>" class="form-control mb-3" id="Email_<?= $row['Id'] ?>" value="<?= $row['Email'] ?>">

                                            <div class="d-flex md-flex-row gap-3 mb-3">
                                                <input placeholder="Tel Fixe" type="tel" name="fixe_<?= $row['Id'] ?>" class="form-control" id="fixe_<?= $row['Id'] ?>" value="<?= $row['tel_fixe'] ?>">
                                                <input placeholder="Tel Portable" type="tel" name="portable_<?= $row['Id'] ?>" class="form-control" id="portable_<?= $row['Id'] ?>" value="<?= $row['tel_portable'] ?>">
                                            </div>

                                            <input placeholder="Date de Naissance" type="date" name="naissance_<?= $row['Id'] ?>" class="form-control mb-3" id="naissance_<?= $row['Id'] ?>" value="<?= $row['Date_naissance'] ?>">

                                            <div class="d-flex md-flex-row gap-3 mb-3">
                                                <input placeholder="Adresse" type="text" name="adresse_<?= $row['Id'] ?>" class="form-control" id="adresse_<?= $row['Id'] ?>" value="<?= $row['Adresse'] ?>">
                                                <input placeholder="Complément d'adresse" type="text" name="adresse1_<?= $row['Id'] ?>" class="form-control" id="adresse1_<?= $row['Id'] ?>" value="<?= $row['Adresse_1'] ?>">
                                            </div>

                                            <div class="d-flex md-flex-row gap-3 mb-3">
                                                <input placeholder="Code Postal" type="text" name="postal_<?= $row['Id'] ?>" class="form-control" id="postal_<?= $row['Id'] ?>" value="<?= $row['Code_postal'] ?>">
                                                <input placeholder="Ville" type="text" name="ville_<?= $row['Id'] ?>" class="form-control" id="ville_<?= $row['Id'] ?>" value="<?= $row['ville'] ?>">
                                            </div>
                                        </div>

                                    </div>
                                    <div class="modal-footer">
                                        <button type="submit" class="btn btn-primary" id="btn_<?php print $row['Id'] ?>" name="sauces_<?php print $row['Id'] ?>">Créer</button>

                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                <?php

                } ?>
            </tbody>

        </table>
    </section>
    <script src="js/tags.js"></script>
    <script src="js/script.js"></script>
</body>

</html>