<?php
// require '../../class/Gestionnaire.php';
require  ".." . DIRECTORY_SEPARATOR . ".." . DIRECTORY_SEPARATOR . ".." . DIRECTORY_SEPARATOR . ".." . DIRECTORY_SEPARATOR . 'class' . DIRECTORY_SEPARATOR . 'Gestionnaire.php';
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

    if($gst->verifDestinaireUtiliser($_GET['id_destinataire'])){
        //Dans le cas ou le estinataire est utilisé dans une facture ou un devis :
        ?>
        <script>alert("Le destinaire selectionner est utiliser, pour pouvoir supprimer ce destinataire veuillez supprimer les factures ou les devis utilisant ce destinataire")</script>
        <?php
    }else{
        //Dans le cas ou le destinataire n'est pas utiliser :
        //Fonction de suppression dans la Base de données : 
        $gst->suppDestinataire($_GET["id_destinataire"], $_SESSION['compte']['user']->getUser_id());
    }

    
    



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