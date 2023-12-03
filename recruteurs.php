<?php
session_start();
require_once('php/config/config.php');

$CV = new CV();
$CV->connexion();

// test table agence pour creation session avec mail et password 
//if (isset($_POST["sauce"])) {
   // if (empty($_POST["mail"] && $_POST["password"])) {
       // echo "vide";
   // } else {
       // $requestDone = $CV->insertUser(["inputemail" => $_POST["mail"], "inputpassword" => $_POST['password']]);
   // }
//}

if (isset($_POST["sauces"])) {
    //si les champ nom/prenom/mail/naissance/portable sont vide (empty)
    if (empty($_POST["Nom"] && $_POST["Prenom"] && $_POST["mail"] && $_POST["naissance"] && $_POST["portable"])) {
        //alors ecrire
        echo "vide";
        //sinon
    } else {
        //un minimum de 5 competences a entrer et un maximum de 10 
        if (count($_POST['tags_new']) >= 5 && count($_POST['tags_new']) <= 10) {
            // création d'un candidat
            $requestDone = $CV->insertCandidat(["inputNom" => $_POST["Nom"], "inputPrenom" => $_POST["Prenom"], "inputAge" => "", "inputDate" => $_POST["naissance"], "inputAdresse" => $_POST["adresse"], "inputAdresse_1" => $_POST["adresse1"], "inputPostal" => $_POST["postal"], "inputVille" => $_POST["ville"], "inputPortable" => $_POST["portable"], "inputFixe" => $_POST["fixe"], "inputMail" => $_POST["mail"], "inputProfil" => "", "inputWeb" => "", "inputLink" => "", "inputVia" => "", "inputFace" => ""]);

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
        import Tags from "https://cdn.jsdelivr.net/gh/lekoala/bootstrap5-tags@master/tags.js";
        Tags.init("select");
    </script>
    <script src="js/app.js"></script>
    <title>CV Thèque</title>
</head>

<body>
    <!--<form method="post">
        <div class="mb-3">
            <label for="" class="form-label">Email address</label>
            <input type="email" name="mail" class="form-control" id="Email">
            <label for="" class="form-label">Password</label>
            <input type="password" name="password" class="form-control" id="password">
            <button type="submit" class="btn btn-primary" name="sauce">Primary</button>
        </div>
    </form> -->

    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal">
        Créer nouveau Candidat
    </button>

    <!-- Modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Création Candidat</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="post">
                        <div class="mb-3">
                            <label for="" class="form-label">Nom</label>
                            <input type="text" name="Nom" class="form-control" id="Nom">

                            <label for="" class="form-label">Prénom</label>
                            <input type="text" name="Prenom" class="form-control" id="Prenom">

                            <label for="" class="form-label">E-mail</label>
                            <input type="email" name="mail" class="form-control" id="Email1">

                            <label for="" class="form-label">Telephone Fixe</label>
                            <input type="tel" name="fixe" class="form-control" id="fixe">

                            <label for="" class="form-label">Telephone Portable</label>
                            <input type="tel" name="portable" class="form-control" id="portable">

                            <label for="" class="form-label">Date de naissance</label>
                            <input type="date" name="naissance" class="form-control" id="naissance">

                            <label for="" class="form-label">Adresse</label>
                            <input type="text" name="adresse" class="form-control" id="adresse">

                            <label for="" class="form-label">Complement d'adresse</label>
                            <input type="text" name="adresse1" class="form-control" id="adresse1">

                            <label for="" class="form-label">Code Postal</label>
                            <input type="text" name="postal" class="form-control" id="postal">

                            <label for="" class="form-label">Ville</label>
                            <input type="text" name="ville" class="form-control" id="ville">

                            <div class="row mb-3 g-3">
                                <div class="col-md-4 w-100">
                                    <label for="validationTagsNew" class="form-label">Compétences</label>
                                    <select class="form-select" id="validationTagsNew" name="tags_new[]" multiple data-allow-new="true">
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
                    <button type="submit" class="btn btn-primary" name="sauces">Créer mon profil</button>
                </div>
            </div>
        </div>
    </div>
    </form>
    <form method="post">
        <button type="submit" class="btn btn-primary" name="All"> Afficher tous les candidats</button>
    </form>
    <table id="myTable" style="width : 100%">
        <thead>
            <th></th>
            <th>Nom</th>
            <th>Prenom</th>
            <th>Age</th>
            <th>Date_naissance</th>
            <th>Adresse</th>
            <th>Adresse_1</th>
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
            $touslescandidats = $CV->getAll();
            foreach($touslescandidats as $row){
                if(isset($_POST["Supprimer" . $row['Id']])){
                    $SuppDone = $CV->deleteCandidat(["inputId" => $row['Id']]);
                }
                
            }
            foreach ($touslescandidats as $row) {
                if (isset($_POST["All"])) {
            ?>
                    <tr>
                        <td>
                            <form method="post">
                                <button type="submit" class="btn btn-primary" name="Modif<?= $row["Id"]; ?>"> Modifier</button>
                                <button type="submit" class="btn btn-danger" name="Supprimer<?= $row["Id"]; ?>"> Supprimer</button>
                            </form>
                        </td>
                        <td><?= strtoupper($row["Nom"]) ?></td>
                        <td><?= $row["Prenom"] ?></td>
                        <td><?= $row["Age"] ?></td>
                        <td><?= $row["Date_naissance"] ?></td>
                        <td><?= $row["Adresse"] ?></td>
                        <td><?= $row["Adresse_1"] ?></td>
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
            <?php
                }
            } ?>
        </tbody>
    </table>
    <script src="js/tags.js"></script>
    <script src="js/script.js"></script>
</body>

</html>