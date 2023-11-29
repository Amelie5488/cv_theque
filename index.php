<?php
session_start();
require_once('php/config/config.php');

$CV = new CV();
$CV->connexion();

if (isset($_POST["sauce"])) {
    if (empty($_POST["mail"] && $_POST["password"])) {
        echo "vide";
    } else {
        $requestDone = $CV->insertUser(["inputemail" => $_POST["mail"], "inputpassword" => $_POST['password']]);
    }
}

if (isset($_POST["sauces"])) {
    if (empty($_POST["Nom"] && $_POST["Prenom"] && $_POST["mail"] && $_POST["naissance"] && $_POST["portable"])) {
        echo "vide";
    } else {
        $requestDone = $CV->insertCandidat(["inputNom" => $_POST["Nom"], "inputPrenom" => $_POST["Prenom"], "inputAge" => "", "inputDate" => $_POST["naissance"], "inputAdresse" => $_POST["adresse"], "inputAdresse_1" => $_POST["adresse1"], "inputPostal" => $_POST["postal"], "inputVille" => $_POST["ville"], "inputPortable" => $_POST["portable"], "inputFixe" => $_POST["fixe"], "inputMail" => $_POST["mail"], "inputProfil" => "", "input1" => "", "input2" => "", "input3" => "", "input4" => "", "input5" => "", "input6" => "", "input7" => "", "input8" => "", "input9" => "", "input10" => "", "inputWeb" => "", "inputLink" => "", "inputVia" => "", "inputFace" => ""]);
        print "ok";
    }
}


?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js" integrity="sha384-BBtl+eGJRgqQAUMxJ7pMwbEyER4l1g+O15P+16Ep7Q9Q+zqX6gSbd85u4mG4QzX+" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <script type="module">
    import Tags from "https://cdn.jsdelivr.net/gh/lekoala/bootstrap5-tags@master/tags.js";
    Tags.init("select");
  </script>
    <script src="js/app.js"></script>
    <title>CV Thèque</title>
</head>

<body>
    <form method="post">
        <div class="mb-3">
            <label for="" class="form-label">Email address</label>
            <input type="email" name="mail" class="form-control" id="Email">
            <label for="" class="form-label">Password</label>
            <input type="password" name="password" class="form-control" id="password">
            <button type="submit" class="btn btn-primary" name="sauce">Primary</button>
        </div>
    </form>

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
                                    <label for="validationTagsNew" class="form-label">Tags (allow new)</label>
                                    <select class="form-select" id="validationTagsNew" name="tags_new[]" multiple data-allow-new="true">
                                        <?php foreach ($CV->getCompetence() as $row) { ?>
                                            <option value="<?php print $row['Nom']; ?>"><?php print $row['Nom']; ?></option>
                                        <?php } ?>
                                    </select>
                                    <div class="invalid-feedback">Please select a valid tag.</div>
                                </div>
                            </div>


                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                    <button type="submit" class="btn btn-primary" name="sauces">Sauvegarder</button>
                </div>
            </div>
        </div>
    </div>
    <script src="js/tags.js"></script>
</body>

</html>