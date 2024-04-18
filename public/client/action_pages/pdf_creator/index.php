<?php
//Cette page sert a rediriger les utilisateur vers le bon model de facture

// require '../../class/Gestionnaire.php';
require  ".." . DIRECTORY_SEPARATOR . ".." . DIRECTORY_SEPARATOR . ".." . DIRECTORY_SEPARATOR . ".." . DIRECTORY_SEPARATOR . 'class' . DIRECTORY_SEPARATOR . 'Gestionnaire.php';
$gst = new Gestionnaire;


//Verification de la connexion :
session_start();
if(!$gst->verifConnexionClient($_SESSION)){
    echo "Error";
    ?>
    <script>window.location.replace("../../../../deconnexion.php");</script>
    <?php
}

//Verification du model de facture ou devis :
if(isset($_GET['numFactureDevis'])){
    if($_GET['numFactureDevis'] != ""){
        $model = $gst->getModelFactureDevis($_GET['numFactureDevis']);
        //Redirection en fonction du model du devis ou facture
        switch($model){
            case "1":
                ?>
                <script>window.location.replace("model1.php?numFactureDevis=<?php echo $_GET['numFactureDevis']; ?>");</script>
                <?php
                break;
            case "2":
                ?>
                <script>window.location.replace("model2.php?numFactureDevis=<?php echo $_GET['numFactureDevis']; ?>");</script>
                <?php

        }
        
    }
    else{
        echo "erreur";
    }
}
else{
    echo "Error";
    ?>
    <script>window.location.replace("../../../../deconnexion.php");</script>
    <?php
}

?>