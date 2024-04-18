<?php
// require '../../class/Gestionnaire.php';
require  ".." . DIRECTORY_SEPARATOR . ".." . DIRECTORY_SEPARATOR . ".." . DIRECTORY_SEPARATOR . ".." . DIRECTORY_SEPARATOR . 'class' . DIRECTORY_SEPARATOR . 'Gestionnaire.php';
$gst = new Gestionnaire;

session_start();
// var_dump($_SESSION);

//Verification de la connexion :
if(!$gst->verifConnexionClient($_SESSION)){
    ?>
    <script>window.location.replace("../../../../deconnexion.php");</script>
    <?php
}else{
    if(isset($_GET['numDevis'])){
        if($_GET['numDevis'] != ""){
            echo "Veuillez patienter, la convertion de votre devis est en cours...";
            $gst->convertFactureToDevisById($_GET['numDevis']);
            ?>
            <script>window.location.replace("../../index.php");</script>
            <?php
        }else{
            //header('Location:../newFacture/clearSessionFacture.php');
            ?>
            <script>window.location.replace("../../../../deconnexion.php");</script>
            <?php
        }
    }else{
        //header('Location:../newFacture/clearSessionFacture.php');
        ?>
        <script>window.location.replace("../../../../deconnexion.php");</script>
        <?php
    }
}





?>