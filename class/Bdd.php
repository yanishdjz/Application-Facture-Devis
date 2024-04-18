<?php
class Bdd
{
    private $dsn='mysql:dbname=projet_bts;host=localhost';
    private $login='root';
    private $motDePasse='';

    private function getDsn(){
        return $this->dsn;
    }

    private function getLogin(){
        return $this->login;
    }

    private function getMotDePasse(){
        return $this->motDePasse;
    }



    //Execution d'une requete sans valeur à proteger :
    //-----------Important-----------
    //Ne pas utiliser pour une requete dons un ou plusieurs champs sont à saisir
    public function requeteBdd($rqt){
        $bdd = new Bdd();
        $dsn = $bdd->getDsn();
        $login = $bdd->getLogin();
        $motDePasse = $bdd->getMotDePasse();

        try{
            $cnx = new PDO($dsn, $login, $motDePasse,
                    array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'));
        }
        catch (PDOException $e){
            die('Erreur : ' . $e->getMessage());
        }

        $sql = $cnx->prepare($rqt);
        $sql->execute();
        $resultat = $sql->fetchAll(PDO::FETCH_ASSOC);

        return $resultat;
    }



    //Execution d'une requete proteger avec un retour de la base de données:
    //-----------Important-----------
    //$rqt doit contenire la requete mySql
    //$lesValeurs doivent contenir un array des bindValue
    public function rqtProteger(string $rqt, array $lesValeurs){
        $bdd = new Bdd();
        $dsn = $bdd->getDsn();
        $login = $bdd->getLogin();
        $motDePasse = $bdd->getMotDePasse();

        try{
            $cnx = new PDO($dsn, $login, $motDePasse,
                    array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'));
        }
        catch (PDOException $e){
            die('Erreur : ' . $e->getMessage());
        }

        $sql = $cnx->prepare($rqt);//Preparation de la requete
        //Chaque valeur de la liste remplace un ? dans la requete mySql
        //Permet d'eviter les injection sql
        for ($i = 0; $i <= count($lesValeurs)-1; $i++) {
            $sql->bindValue($i+1,$lesValeurs[$i]);
        }

        $sql->execute();
        //Recupere la totalité
        $resultat = $sql->fetchAll(PDO::FETCH_ASSOC);

        return $resultat;
    }



    //Fonction de test
    public function connexionBDD(){
        $bdd = new Bdd();
        $dsn = $bdd->getDsn();
        $login = $bdd->getLogin();
        $motDePasse = $bdd->getMotDePasse();

        try{
            $cnx = new PDO($dsn, $login, $motDePasse,
                    array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'));
        }
        catch (PDOException $e){
            die('Erreur : ' . $e->getMessage());
        }

        return $cnx;
    }

    //Execution d'une requete proteger sans retour de la base de données (ex: UPDATE) :
    //-----------Important-----------
    //$rqt doit contenire la requete mySql
    //$lesValeurs doivent contenir un array des bindValue
    public function rqtProtegerSansReturn(string $rqt, array $lesValeurs){
        $bdd = new Bdd();
        $dsn = $bdd->getDsn();
        $login = $bdd->getLogin();
        $motDePasse = $bdd->getMotDePasse();

        try{
            $cnx = new PDO($dsn, $login, $motDePasse,
                    array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'));
        }
        catch (PDOException $e){
            die('Erreur : ' . $e->getMessage());
        }

        $sql = $cnx->prepare($rqt);//Preparation de la requete
        //Chaque valeur de la liste remplace un ? dans la requete mySql
        //Permet d'eviter les injection sql
        for ($i = 0; $i <= count($lesValeurs)-1; $i++) {
            $sql->bindValue($i+1,$lesValeurs[$i]);
        }

        $sql->execute();
    }









}
?>