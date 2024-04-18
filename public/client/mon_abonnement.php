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

$lesFactures = $gst->getAllFacturesByUserId($_SESSION['compte']["user"]->getUser_id());
// var_dump($lesFactures);
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
        <div class="titre">
            <h1>Espace Abonnement</h1>
            <hr>
        </div>
        <div class="container">
            <?php
            // if(!$_SESSION['compte']['user']->getUser_abonnement()){}
            if($_SESSION['compte']['user']->getUser_abonnement()){
                if($_SESSION['compte']['user']->getUser_abonnement()->getAbo_statut() == "true"){
                    $date_fin_abo = (new DateTime($_SESSION['compte']['user']->getUser_abonnement()->getDate_abonnement()))->format('d/m/Y');
                    //Dans le cas ou l'utilisateur a un abonnement
                ?>
                <div class="text-center" style="margin-top:20vh">
                    <h4>Vous êtes Abonné à nos services</h4>
                    <p>Votre aboonement prendra fin le : <?php echo $date_fin_abo; ?></p>
                    <p><small>Vous pourrez étendre votre abonnement une fois fini</small></p>
                    
                </div>
    
                <?php
                }else{
                    //Dans le cas un l'utilisateur n'a pas d'abonnement actif
                    $allAbonnement = $gst->getAllTypeAbonnements();
                ?>
                <br><br>
                <h4 class="text-center">Vous n'êtes pas encore abonné à nos services</h4>
                <hr style="width:30%; margin-left:auto; margin-right:auto;">
                <p class="text-center">Choisisez votre future abonnement</p>
                <div class="row justify-content-center">
                    <?php
                    foreach($allAbonnement as $unAbonnement){
                        // var_dump($unAbonnement);
                        ?>
                        <div class="col-sm-4">
                            <div class="shadow p-3 mb-5 bg-body rounded">
                                <h3 class="text-center" style="text-transform:capitalize"><?php echo$unAbonnement['nom'];?></h3>
                                <br>
                                <p class="text-center">
                                    <?php echo $unAbonnement['nb_max_facture'];?> Facture par mois <br>
                                    <?php echo $unAbonnement['nb_max_devis'];?> Devis par mois <br>
                                    Assistance en ligne
                                </p>
                                <h2 class="text-center"><?php echo $unAbonnement['prix_abo'];?>€/mois</h2>
                                <div class="text-center">
                                    <br>
                                    <a href="paiement.php?forfait=<?php echo $unAbonnement['id']; ?>" type="button" class="btn btn-outline-primary">Choisir</a>
                                </div>
                            </div>
                        </div>
                        <?php
                    }
                    ?>
                </div>
                <?php
                }//Fin else
            }
            else{
                //Dans le cas un l'utilisateur n'a pas d'abonnement actif
                $allAbonnement = $gst->getAllTypeAbonnements();
            ?>
            <br><br>
            <h4 class="text-center">Vous n'êtes pas encore abonné à nos services</h4>
            <hr style="width:30%; margin-left:auto; margin-right:auto;">
            <p class="text-center">Choisisez votre future abonnement</p>
            <div class="row justify-content-center">
                <?php
                foreach($allAbonnement as $unAbonnement){
                    // var_dump($unAbonnement);
                    ?>
                    <div class="col-sm-4">
                        <div class="shadow p-3 mb-5 bg-body rounded">
                            <h3 class="text-center" style="text-transform:capitalize"><?php echo$unAbonnement['nom'];?></h3>
                            <br>
                            <p class="text-center">
                                <?php echo $unAbonnement['nb_max_facture'];?> Facture par mois <br>
                                <?php echo $unAbonnement['nb_max_devis'];?> Devis par mois <br>
                                Assistance en ligne
                            </p>
                            <h2 class="text-center"><?php echo $unAbonnement['prix_abo'];?>€/mois</h2>
                            <div class="text-center">
                                <br>
                                <a href="paiement.php?forfait=<?php echo $unAbonnement['id']; ?>" type="button" class="btn btn-outline-primary">Choisir</a>
                            </div>
                        </div>
                    </div>
                    <?php
                }
                ?>
            </div>
            <?php
            }//Fin else
            ?>
        </div><!-- Fin container principale -->

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>


    </body>
</html>