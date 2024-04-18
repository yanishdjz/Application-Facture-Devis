<?php
// require '../../class/Gestionnaire.php';
require ".." . DIRECTORY_SEPARATOR . ".." . DIRECTORY_SEPARATOR . ".." . DIRECTORY_SEPARATOR . ".." . DIRECTORY_SEPARATOR . 'class' . DIRECTORY_SEPARATOR . 'Gestionnaire.php';
$gst = new Gestionnaire;

session_start();
//Verification de la connexion :
if(!$gst->verifConnexionClient($_SESSION)){
    ?>
    <script>window.location.replace("../../../../deconnexion.php");</script>
    <?php
}
// var_dump();

if(isset($_POST["raison_sociale"]) && isset($_POST["adresse_rue"]) && isset($_POST["adresse_code"]) && isset($_POST["adresse_ville"])){
    
    //Si le code TVA INTRA n'existe pas :
    if($_POST["num_tva_intra"] == ""){
        $_POST["num_tva_intra"] = null;
    }
    // var_dump($_POST);

    //Ressembler dans la meme variable le code postale ainsi que la ville :
    $adresse_code_ville = $_POST["adresse_code"]." ".$_POST["adresse_ville"];
    
    //Fonction d'ajout dans la Base de données : 
    $gst->ajouterNewDestinataire($_SESSION['compte']['user']->getUser_id(), $_POST["raison_sociale"], $_POST["adresse_rue"], $adresse_code_ville, $_POST["num_tva_intra"]);
    
    //Pour savoir sur quel page faire la redirection nous prenons un varriable passer en GET
    if(isset($_GET['retour'])){
        if($_GET['retour'] == "gerer_clients_vendeurs"){
            ?>
            <script>window.location.replace("../../gerer_clients_vendeurs.php");</script>
            <?php
        }
        if($_GET['retour'] == "ajouterDestinataire"){
            ?>
            <script>window.location.replace("../../ajouterDestinataire.php");</script>
            <?php
        }
    }else{
        ?>
            <script>window.location.replace("../../gerer_clients_vendeurs.php");</script>
        <?php
    }
    
}


?>