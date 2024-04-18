<?php
// require '../../class/Gestionnaire.php';
require  ".." . DIRECTORY_SEPARATOR . ".." . DIRECTORY_SEPARATOR . 'class' . DIRECTORY_SEPARATOR . 'Gestionnaire.php';
$gst = new Gestionnaire;

session_start();
//Verification de la connexion :
if(!$gst->verifConnexionClient($_SESSION)){
    ?>
    <script>window.location.replace("../../deconnexion.php");</script>
    <?php
}

$lesDevis = $gst->getAllDevisByUserId($_SESSION['compte']["user"]->getUser_id());

//Cette fonction permet de mettre à jour les données d'abonnement du client à chaque rafrechissement :
$gst->updateAbonnementByUserId($_SESSION['compte']["user"]->getUser_id());
// var_dump($_SESSION);


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
                <h1>DEVIS</h1>
                <hr>
            </div>
            <div class="row justify-content-center">
                <div class="col-md-8 text-center">
                    <br>
                    <?php
                    // var_dump($lesDevis);
                    foreach ($lesDevis as $unDevis){
                        ?>
                        <div class="shadow-sm p-3 mb-5 bg-body rounded">
                            <div class="row">
                                <div class="col-md-3" onMouseOver="voirInfosFactureDevis(<?php echo $_SESSION['compte']["user"]->getUser_id();?>,<?php echo $unDevis->getId(); ?>)" onmouseleave="fermetureInfos()">
                                    <p>N° <?php echo $unDevis->getNum_fact_devis(); ?></p>
                                    <hr>
                                </div>
                                <div class="col-md-3">
                                    <p><?php echo date("d/m/Y", strtotime($unDevis->getDate_facture()));  ?></p>
                                    <hr>
                                </div>
                                <div class="col-md-3">
                                    <p>De <b><?php echo $unDevis->getLeVenduer()->getDenomination(); ?></b> à <b><?php echo $unDevis->getLeDestinataire()->getDest_denomination(); ?></b> </p>
                                    <hr>
                                </div>
                                <div class="col-md-3">
                                    <p><?php echo $unDevis->getTotal_ttc(); ?> € TTC</p>
                                    <hr>
                                </div>
                            </div>
                            <div class="text-center">
                                <!-- Button trigger modal -->
                                <a href="action_pages/pdf_creator/index.php?numFactureDevis=<?php echo $unDevis->getId();?>" type="button" class="btn btn-outline-success">Consulter</a>
                                <a href="action_pages/action_fact_devis_bdd/supp_fact_devis.php?numFactureDevis=<?php echo $unDevis->getId();?>" type="button" class="btn btn-outline-danger">Supprimer</a>
                            </div>
                            
                        </div>
                        <?php
                    }
                    ?>
                </div>
            </div>
        </div>




    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
    </body>
</html>