<?php

class CV
{

    private $host = "127.0.0.1";
    private $user = "root";
    private $password = "";
    private $database = "cv_theq";
    private $charset = "utf8";

    private $bdd;

    //stockage de l'erreur éventuelle du serveur mysql
    private $error;

    /* méthode de connexion à la base de donnée */
    public function connexion()
    {

        try {
            // On se connecte à MySQL
            $this->bdd = new PDO('mysql:host=' . $this->host . ';dbname=' . $this->database . ';charset=' . $this->charset, $this->user, $this->password);
        } catch (Exception $e) {
            // En cas d'erreur, on affiche un message et on arrête tout
            $this->error = 'Erreur : ' . $e->getMessage();
        }
    }

    /* méthode qui renvoit tous les résultats sous forme de tableau de la requête passée en paramètre */
    public function getResults($query)
    {
        $results = array();

        $stmt = $this->bdd->query($query);

        if (!$stmt) {
            $this->error = $this->bdd->errorInfo();
            return false;
        } else {
            return $stmt->fetchAll();
        }
    }

    // creation d'un compte sur l'index 
    public function insertUser($b = [])
    {
        $sql = "INSERT into compte (Mail,Password,profil_id) values (:inputemail,:inputpassword,:inputId)";
        $done = $this->bdd->prepare($sql);
        $done->execute($b);
    }

    //création d'un candidat sur la page recruteur
    public function insertCandidat($candidat = [])
    {

        $sql = "INSERT into tablename (Nom, Prenom, Age, Date_naissance, Adresse, Adresse_1, Code_postal, ville, tel_portable, tel_fixe, Email, Profil, Site_Web, Profil_Linkedin, Profil_Viadeo, Profil_facebook, CV) values (:inputNom,:inputPrenom,:inputAge,:inputDate,:inputAdresse,:inputAdresse_1,:inputPostal,:inputVille,:inputPortable,:inputFixe,:inputMail,:inputProfil,:inputWeb,:inputLink,:inputVia,:inputFace, :inputCv)";
        $done =  $this->bdd->prepare($sql);
        $done->execute($candidat);
    }

    //recupere les compétences pour afficher les tags
    public function getCompetence()
    {

        $sql = "SELECT * FROM competences";
        $done = $this->bdd->prepare($sql);
        $done->execute();
        //retourner toutes les valeurs de la table//
        $return = $done->fetchAll();
        return $return;
    }

    //recupere tout de la table pricipal pour le bouton afficher tous les candidats 
    public function getAll()
    {

        $sql = "SELECT * FROM tablename";
        $done = $this->bdd->prepare($sql);
        $done->execute();
        $return = $done->fetchAll();
        return $return;
    }

    //recupere tout de la table pricipal pour le bouton afficher tous les candidats 
    //utilise aussi pour la connection du compte 
    public function getTable($param = [])
    {

        $sql = "SELECT * FROM tablename where Email = :inputEmail";
        $done = $this->bdd->prepare($sql);
        $done->execute($param);
        $return = $done->fetch();
        return $return;
    }
    //recupere id max donc dernier id crée car lors de la creation de candidats si ils ont le meme nom alors toute la ligne est modifié par la nouvelle entrée 
    public function getId()
    {

        $sql = "SELECT MAX(Id) from tablename ";
        $done = $this->bdd->prepare($sql);
        $done->execute();
        $return = $done->fetchAll();
        return $return;
    }

    // pour inserer les nouvelles compétence qui n'existe pas dans la table compétence
    public function insertCompt($i, $a = [])
    {
        $v = $i + 1;
        $sql = "UPDATE tablename SET Competence_$v = :inputTag$i where Id = :monID";
        $done =  $this->bdd->prepare($sql);
        $done->execute($a);
    }
    //si l'utilisateur ecrit une compétence qui n'est pas présente dans la table alors les nouvelles compétences doivent etre insérées dans la table 
    public function insertTag($a = [])
    {
        $sql = "INSERT into competences (Nom) values (:inputNom)";
        $done = $this->bdd->prepare($sql);
        $done->execute($a);
    }

    //Une fois que les nouvelles compétences ont été inséré je les récupere pour pouvoir les afficher en TAG 
    public function getCompetences($param = [])
    {

        $sql = "SELECT * FROM competences where Nom = :monNomCompt";
        $done = $this->bdd->prepare($sql);
        $done->execute($param);
        //retourner toutes les valeurs de la table//
        $return = $done->fetch();
        return $return;
    }
    // supprimer un candidat sur la page recruteur 
    public function deleteCandidat($suppr = [])
    {

        $sql = "DELETE from tablename where Id = :inputId";
        $done = $this->bdd->prepare($sql);
        $done->execute($suppr);
    }

    //pour etablir la connection 
    public function gettout($connect = [])
    {

        $sql = "SELECT * FROM compte where Mail= :inputMail";
        $done = $this->bdd->prepare($sql);
        $done->execute($connect);
        $return = $done->fetch();
        return $return;
    }

    // quand je creer un compte avec une adresse mail j'ai un Id qui se creer aussi 
    // utiliser sur la page candidat 
    public function insertId($param = [])
    {
        $sql = "UPDATE compte SET profil_id = :inputId where Mail = :monMail";
        $done =  $this->bdd->prepare($sql);
        $done->execute($param);
    }

    // afficher toute les infos de mon profil sur la page candidat 
    public function getmonprofil($param = [])
    {
        $sql = "SELECT * from compte inner join tablename on (tablename.Id=compte.profil_id) where Mail = :inputMail";
        $done = $this->bdd->prepare($sql);
        $done->execute($param);
        $return = $done->fetch();
        return $return;
    }

    // pour modifier le candidat dans la page recruteur
    public function insertprofil($param = [])
    {

        $sql = "UPDATE tablename SET Nom = :inputNom , Prenom = :inputPrenom, Date_naissance = :inputNaissance, Age = :inputAge, Adresse = :inputAdresse, Adresse_1 = :inputAdresse1, Code_postal = :inputPostal, ville = :inputVille, tel_portable = :inputPortable, tel_fixe = :inputFixe, Email = :inputMail, Profil = :inputProfil where Id = :monId";
        $done =  $this->bdd->prepare($sql);
        $done->execute($param);
    }
    // pour modifier le candidat dans la page recruteur
    public function insertprofil1($param = [])
    {

        $sql = "UPDATE tablename SET Nom = :inputNom , Prenom = :inputPrenom, Date_naissance = :inputNaissance, Age = :inputAge, Adresse = :inputAdresse, Adresse_1 = :inputAdresse1, Code_postal = :inputPostal, ville = :inputVille, tel_portable = :inputPortable, tel_fixe = :inputFixe, Email = :inputMail, Profil = :inputProfil, Site_Web = :inputWeb, Profil_Linkedin = :inputLink, Profil_Viadeo = :inputVid, Profil_facebook = :inputFB where Id = :monId";
        $done =  $this->bdd->prepare($sql);
        $done->execute($param);
    }
    // pour modifier le candidat dans la page recruteur
    public function updateCV($param = [])
    {

        $sql = "UPDATE tablename SET CV = :inputCV where Id = :monId";
        $done =  $this->bdd->prepare($sql);
        $done->execute($param);
    }

    public function deco()
    {
        $this->bdd = NULL;
    }
}
