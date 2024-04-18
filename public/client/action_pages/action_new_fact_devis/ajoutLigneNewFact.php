<?php
session_start();
require ".." . DIRECTORY_SEPARATOR .".." . DIRECTORY_SEPARATOR . ".." . DIRECTORY_SEPARATOR . ".." . DIRECTORY_SEPARATOR . 'class' . DIRECTORY_SEPARATOR . 'Ligne_facture_devis.php';

if(isset($_POST['desc']) && isset($_POST['qte']) && isset($_POST['unite']) && isset($_POST['prix']) ){
    // var_dump($_SESSION['newFactureDevis']['tva_auto_liquidation']);

    $laLigne = new Ligne_facture_devis($_POST['desc'], (int)$_POST['qte'], $_POST['unite'], (float)$_POST['prix']);
    if(!$_SESSION['newFactureDevis']['tva_auto_liquidation']){
        if(isset($_POST['tva'])){
            $laLigne->setTva_ligne($_POST['tva']);
        }else{
            echo '<p>Une erreur technique est survenue, veuillez relancer la page</p>';
        }
    }

    // echo $_POST['desc'];
    // echo $_POST['qte'];
    // echo $_POST['unite'];
    // echo $_POST['prix'];
    // echo $tva;



    $_SESSION['newFactureDevis']['ligneFactureDevis'][] = $laLigne;
    //header('Location:detailsNewFacture.php');
      ?>
      <script>window.location.replace("../../detailsNewFactureDevis.php");</script>
      <?php


}else{
    echo '<p>Une erreur technique est survenue, veuillez relancer la page</p>';
}

?>