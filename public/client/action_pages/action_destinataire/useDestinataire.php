<?php
// require '../../class/Gestionnaire.php';
require ".." . DIRECTORY_SEPARATOR .".." . DIRECTORY_SEPARATOR .".." . DIRECTORY_SEPARATOR . ".." . DIRECTORY_SEPARATOR . 'class' . DIRECTORY_SEPARATOR . 'Gestionnaire.php';
$gst = new Gestionnaire;

session_start();
//Verification de la connexion :
if(!$gst->verifConnexionClient($_SESSION)){
    ?>
    <script>window.location.replace("../../../../deconnexion.php");</script>
    <?php
}
// var_dump();

if(isset($_GET['id_destinataire'])){
    
    //Si le code TVA INTRA n'existe pas :
    if($_GET["id_destinataire"] == ""){
        ?>
            <script>window.location.replace("../../../../deconnexion.php");</script>
        <?php
    }
    //Fonction de suppression dans la Base de données : 
    $_SESSION['newFactureDevis']['id_destinataire'] = $_GET["id_destinataire"];
    // var_dump($_SESSION['newFactureDevis']);
    echo "Veuillez patientez la creation de votre ".$_SESSION['newFactureDevis']['type']." en cours...";
    

    ?>
    <script>window.location.replace("../action_fact_devis_bdd/ajoutFactureDevisBDD.php");</script>
    <?php
}

?>