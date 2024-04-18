<?php
//Cette page ne detruit pas la session mais efface la varriable de session appler $_SESSION['ligneFacture'],
//se qui a pour but d'effacer la facture enregistrer
session_start();
if(!isset($_SESSION['newFactureDevis'])){
  echo '<script>window.alert("Une erreur de passage de données est survenue")</script>';
}else{
  $_SESSION['newFactureDevis'] = [];
//   var_dump($_SESSION['ligneFacture']);
    //header('Location:../index.php');
    ?>
    <script>window.location.replace("../../nouvelle_facture_devis.php");</script>
    <?php
  
}



?>