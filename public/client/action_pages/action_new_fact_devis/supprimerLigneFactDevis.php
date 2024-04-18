<?php
session_start();


if (isset($_GET['ligne'])){
    $i = $_GET['ligne'];
    // $lesLignes = $_SESSION['ligneFacture'];
    // unset($_SESSION['ligneFacture'][$i]);
    array_splice($_SESSION['newFactureDevis']['ligneFactureDevis'], $i, 1);
    //header('Location:detailsFacture.php');
    ?>
    <script>window.location.replace("../../detailsNewFactureDevis.php");</script>
    <?php
}

?>

