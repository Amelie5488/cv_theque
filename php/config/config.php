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

    //test avec table agence pour creation session avec mail et password 
    public function insertUser($User = [])
    {
        $sql = "INSERT into agence (email,password) values (:inputemail,:inputpassword)";
       $done =  $this->bdd->prepare($sql);
       $done->execute($User);
    }

    //création candidat
    public function insertCandidat($candidat=[]){

        $sql= "INSERT into tablename (Nom, Prenom, Age, Date_naissance, Adresse, Adresse_1, Code_postal, ville, tel_portable, tel_fixe, Email, Profil, Site_Web, Profil_Linkedin, Profil_Viadeo, Profil_facebook) values (:inputNom,:inputPrenom,:inputAge,:inputDate,:inputAdresse,:inputAdresse_1,:inputPostal,:inputVille,:inputPortable,:inputFixe,:inputMail,:inputProfil,:inputWeb,:inputLink,:inputVia,:inputFace)";
        $done =  $this->bdd->prepare($sql);
        $done->execute($candidat);
    }

    //recupere les compétences pour afficher les tags
    public function getCompetence(){

        $sql= "SELECT * FROM competences";
        $done = $this->bdd->prepare($sql);
        $done->execute();
        //retourner toutes les valeurs de la table//
        $return = $done->fetchAll();  
        return $return;
    }

    //recupere tout de la table pricipal pour le bouton afficher tous les candidats 
    public function getAll(){

        $sql = "SELECT * FROM tablename";
        $done = $this->bdd->prepare($sql);
        $done->execute();
        $return = $done->fetchAll();
        return $return;

    }

    //recupere id max donc dernier id crée car lors de la creation de candidats si ils ont le meme nom alors toute la ligne est modifié par la nouvelle entrée 
    public function getId(){

        $sql = "SELECT MAX(Id) from tablename ";
        $done = $this->bdd->prepare($sql);
        $done->execute();
        $return = $done->fetchAll();
        return $return;
    }

    // pour inserer les nouvelles compétence qui n'existe pas dans la table compétence
    public function insertCompt($i,$a=[]){
        $v = $i + 1 ;
        $sql="UPDATE tablename SET Competence_$v = :inputTag$i where Id = :monID";
        $done =  $this->bdd->prepare($sql);
        $done->execute($a);

    }
    //si l'utilisateur ecrit une compétence qui n'est pas présente dans la table alors les nouvelles compétences doivent etre insérées dans la table 
    public function insertTag($a =[]){
        $sql = "INSERT into competences (Nom) values (:inputNom)";
        $done = $this->bdd->prepare($sql);
        $done->execute($a);
    }

    //Une fois que les nouvelles compétences ont été inséré je les récupere pour pouvoir les afficher en TAG 
    public function getCompetences($param=[]){

        $sql= "SELECT * FROM competences where Nom = :monNomCompt";
        $done = $this->bdd->prepare($sql);
        $done->execute($param);
        //retourner toutes les valeurs de la table//
        $return = $done->fetch();  
        return $return;
    }

    public function deleteCandidat($suppr=[]){

        $sql = "DELETE from tablename where Id = :inputId";
        $done = $this->bdd->prepare($sql);
        $done->execute($suppr);

    }
}
