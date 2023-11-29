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

    public function insertUser($User = [])
    {
        $sql = "INSERT into agence (email,password) values (:inputemail,:inputpassword)";
       $done =  $this->bdd->prepare($sql);
       $done->execute($User);
    }

    public function insertCandidat($candidat=[]){

        $sql= "INSERT into tablename (Nom, Prenom, Age, Date_naissance, Adresse, Adresse_1, Code_postal, ville, tel_portable, tel_fixe, Email, Profil, Competence_1, Competence_2, Competence_3, Competence_4, Competence_5, Competence_6, Competence_7, Competence_8, Competence_9, Competence_10, Site_Web, Profil_Linkedin, Profil_Viadeo, Profil_facebook) values (:inputNom,:inputPrenom,:inputAge,:inputDate,:inputAdresse,:inputAdresse_1,:inputPostal,:inputVille,:inputPortable,:inputFixe,:inputMail,:inputProfil,:input1,:input2,:input3,:input4,:input5,:input6,:input7,:input8,:input9,:input10,:inputWeb,:inputLink,:inputVia,:inputFace)";
        $done =  $this->bdd->prepare($sql);
        $done->execute($candidat);
    }

    public function getCompetence(){

        $sql= "SELECT * FROM competences";
        $done = $this->bdd->prepare($sql);
        $done->execute();
        $return = $done->fetchAll();
        return $return;
    }



}
