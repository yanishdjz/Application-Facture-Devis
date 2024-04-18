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
$lesLignes = $_SESSION['newFactureDevis']['ligneFactureDevis'];
$total_ttc = 0;
foreach ($lesLignes as $laLigne){
    // var_dump($laLigne);
    $total_ttc = $total_ttc + ($laLigne->getQuantite()*$laLigne->getPrix_unitaire_ht());
}

$user_id = $_SESSION['compte']['user']->getUser_id();
$num_Fact_Devis = $_SESSION['newFactureDevis']['numFactureDevis'];

// echo "num :".$_SESSION['newFactureDevis']['numFactureDevis'];
$type = $_SESSION['newFactureDevis']['type'];
$date = $_SESSION['newFactureDevis']['dateFacture'];
$destinataire_id = $_SESSION['newFactureDevis']['id_destinataire'];
$id_vendeur = $_SESSION['newFactureDevis']['leVendeur'];
$model = $_SESSION['newFactureDevis']['leModel'];

if($_SESSION['newFactureDevis']['tva_auto_liquidation'] == true){
    $tva_auto_liquidation = "true";
}else{
    $tva_auto_liquidation = "false";
}

$gst->ajouterFactureDevis($user_id, $num_Fact_Devis, $type, $date, $lesLignes, $destinataire_id , $id_vendeur, $model, $tva_auto_liquidation);
$gst->ajouterLignes($num_Fact_Devis, $user_id, $lesLignes);
// var_dump($_SESSION);
// var_dump($num_Fact_Devis);
// var_dump($lesLignes);
?>
<script>window.location.replace("../../index.php");</script>
