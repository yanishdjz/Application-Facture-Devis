<?php
// require '../../class/Gestionnaire.php';
require  ".." . DIRECTORY_SEPARATOR . ".." . DIRECTORY_SEPARATOR . 'class' . DIRECTORY_SEPARATOR . 'Gestionnaire.php';
$gst = new Gestionnaire;

session_start();
//Verification de la connexion :
// var_dump($_SESSION);
if(!$gst->verifConnexionClient($_SESSION)){
    ?>
    <script>window.location.replace("../../deconnexion.php");</script>
    <?php
}

$lesFactures = $gst->getAllFacturesByUserId($_SESSION['compte']["user"]->getUser_id());
// var_dump($lesFactures);
//Cette fonction permet de mettre à jour les données d'abonnement du client à chaque rafrechissement :
$gst->updateAbonnementByUserId($_SESSION['compte']["user"]->getUser_id());
// var_dump($_SESSION['compte']['user']);

// $test = new User(1, "KJDG", "gnfk,", "tfgdsv", "kjbdvkj");
// var_dump($test);

?>
<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Facture</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
        <link rel="stylesheet" href="../../css/style.css">
    </head>
    <body>
        <?php include 'navbar.html'; ?>
        

        <div class="container">
            <div class="titre">
                <h1>FACTURES</h1>
                <hr>
            </div>
            <div class="row justify-content-center">
                <div class="col-md-8 text-center">
                    <br>
                    <?php
                    // var_dump($lesFactures);
                    foreach ($lesFactures as $uneFacture){
                        ?>
                        <div class="shadow-sm p-3 mb-5 bg-body rounded">
                            <div class="row">
                                <div class="col-md-3" onMouseOver="voirInfosFactureDevis(<?php echo $_SESSION['compte']["user"]->getUser_id();?>,<?php echo $uneFacture->getId(); ?>)" onmouseleave="fermetureInfos()">
                                    <p>N° <?php echo $uneFacture->getNum_fact_devis(); ?></p>
                                    <hr>
                                </div>
                                <div class="col-md-3">
                                    <p><?php echo date("d/m/Y", strtotime($uneFacture->getDate_facture()));  ?></p>
                                    <hr>
                                </div>
                                <div class="col-md-3">
                                    <p>De <b><?php echo $uneFacture->getLeVenduer()->getDenomination(); ?></b> à <b><?php echo $uneFacture->getLeDestinataire()->getDest_denomination(); ?></b> </p>
                                    <hr>
                                </div>
                                <div class="col-md-3">
                                    <p><?php echo $uneFacture->getTotal_ttc(); ?> € TTC</p>
                                    <hr>
                                </div>
                            </div>
                            <div class="text-center">
                                <!-- Button trigger modal -->
                                <a href="action_pages/pdf_creator/index.php?numFactureDevis=<?php echo $uneFacture->getId();?>" type="button" class="btn btn-outline-success">Consulter</a>
                                <a href="action_pages/action_fact_devis_bdd/supp_fact_devis.php?numFactureDevis=<?php echo $uneFacture->getId();?>" type="button" class="btn btn-outline-danger">Supprimer</a>
                            </div>
                            
                        </div>
                        <?php
                    }
                    ?>
                </div>
            </div>
        </div>

        <div id="lesInfos" style="position:absolute;display:block;z-index:5;visibility:hidden;">
            <div class="shadow-none p-3 mb-5 bg-light rounded">
                <h6>
                    <span id="txtNumFactDevis">Facture numero : </span><br>
                    <span id="txtTotalHT">Total HT : </span><br>
                    <span id="txtTotalTVA">Total TVA : </span>
                </h6>
            </div>
        </div>

        <script defer src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>


    </body>
</html>