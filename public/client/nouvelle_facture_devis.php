<?php
// require '../../class/Gestionnaire.php';
require ".." . DIRECTORY_SEPARATOR . ".." . DIRECTORY_SEPARATOR . 'class' . DIRECTORY_SEPARATOR . 'Gestionnaire.php';
$gst = new Gestionnaire;

session_start();
//Verification de la connexion :
if(!$gst->verifConnexionClient($_SESSION)){
    ?>
    <script>window.location.replace("../../deconnexion.php");</script>
    <?php
}

//Cette fonction permet de mettre à jour les données d'abonnement du client à chaque rafrechissement :
$gst->updateAbonnementByUserId($_SESSION['compte']["user"]->getUser_id());
//Cette fonction recupere tous les vendeur de la table vendeur_acheteur par l'id proprietaire :
$lesVendeurs = $gst->getAllVendeurByIdUser($_SESSION['compte']["user"]->getUser_id());


?>
<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Facture</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
        <link rel="stylesheet"  href="../../css/style.css">
    </head>
    <body>
        <?php 
        include 'navbar.html'; 
        if(!$_SESSION['compte']['user']->getUser_abonnement()){
            ?>
            <div class="text-center" style="margin-top:33vh">
                <h4>Vous n'avez pas d'abonnement pour créer des factures</h4>
                <p>Veuillez vous inscrire pour saisire de nouvelles factures</p>
                <a href="mon_abonnement.php" type="button" class="btn btn-outline-primary">S'abonner</a>
            </div>
            <?php
        }else{
            if($_SESSION['compte']['user']->getUser_abonnement()->getAbo_statut() != 'true'){
                ?>
                <div class="text-center" style="margin-top:33vh">
                    <h4>Vous n'avez pas d'abonnement pour créer des factures</h4>
                    <p>Veuillez vous inscrire pour saisire de nouvelles factures</p>
                    <a href="mon_abonnement.php" type="button" class="btn btn-outline-primary">S'abonner</a>
                </div>
                <?php
            }
            else{
                if($_SESSION['compte']['user']->getUser_abonnement()->getAbo_statut() == 'true'){
                    ?>
                <div class="container text-center">
                    <br><br>
                    <br>
                    <h3>Veuillez saisir les informations demandé</h3>
                    <br>
                    <div class="row justify-content-center">
                        <div class="col-md"></div>
                        <div class="col-md-5">
                            <div class="shadow p-3 mb-5 bg-body rounded">
                                <form action="" method="POST">
                                    <div class="mb-3">
                                        <label class="form-label">Numero de facture/devis</label>
                                        <input name="numFactureDevis" type="text" class="form-control" autocomplete="off" Required>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Date de facture</label>
                                        <input name="dateFacture" type="date" class="form-control" Required>
                                    </div>
    
                                    <div class="mb-3">
                                        <?php
                                        if(count($lesVendeurs) < 1){
                                            ?>
                                            <p>Vous n'avez pas de vendeur enregistrer</p>
                                            <a href="gerer_clients_vendeurs.php" type="button" class="btn btn-outline-primary">Ajouter un vendeur</a>
                                            <?php
                                        }else{
                                        ?>
                                        <label class="form-label">Selectionner le vendeur</label>
                                        <select name="leVendeur" class="form-select" aria-label="Default select example">
                                            <?php
                                            foreach($lesVendeurs as $leVendeur){
                                                ?>
                                                <option value="<?php echo $leVendeur['id_vendeur']; ?>"><?php echo $leVendeur['denomination']; ?></option>
                                                <?php
                                            }
                                            ?>
                                        </select>
                                        <?php
                                        }//Fin du else pour choix de vendeur
                                        ?>
                                    </div>
    
                                    <div class="row ">
                                        <div class="col">
                                            <label class="form-label">Selectionner le type</label>
                                            <select name="type" class="form-select" aria-label="Default select example">
                                                <option value="devis" selected>Devis</option>
                                                <option value="facture">Facture</option>
                                            </select>
                                        </div>
                                        <div class="col">
                                            <label class="form-label">Selectionner le modèle</label>
                                            <select name="model" id="selectionModel" class="form-select" aria-label="Default select example">
                                                <option value="1" selected>Modèle 1</option>
                                                <option value="2">Modèle 2</option>
                                            </select>
                                        </div>
                                    </div>
                                    
                                    <br>
    
                                    <div class="form-check form-switch" style="text-align:left">
                                        <input name="tva_auto_liquidation" class="form-check-input" type="checkbox" role="switch" id="flexSwitchCheckDefault">
                                        <label class="form-check-label" for="flexSwitchCheckDefault">TVA Auto liquidation</label>
                                    </div>
                                    <br>
                                    <button type="submit" class="btn btn-outline-success">Suivant</button>
                                </form>
                            </div>
                        </div>
                        <div class="col-md">
                            <img src="" id="img_Illustration_model" style="width:100%">
                        </div>
                    </div>
                </div>
                <?php
                }//fermeture du if abonnement existe
            }//fermeture du else
        }

        
        
        
        //Apres clique sur le bouton submite
        if(isset($_POST['numFactureDevis']) && isset($_POST['dateFacture']) && isset($_POST['type']) && isset($_POST['leVendeur']) && isset($_POST['model'])){
            
            //Creer une valeur d'autorisation :
            //Cette valeur passera a true si l'utilisateur a encore des factures ou devis restant
            //Cette valeur est null par defaut pour eviter de declancher les if(autorisation)
            $autorisation = null;

            if($_POST['type'] == "facture"){
                //Verrifer si l'utilisateur a epuiser toutes ces factures autorisé :
                if($_SESSION['compte']["user"]->getUser_abonnement()->getNb_fact_restant() > 0 ){
                    //Si l'utilisateur a encore des factures restant pour le mois :
                    $autorisation = true;
                }else{
                    $autorisation = false;
                }
            }
            if($_POST['type'] == "devis"){
            //Verrifer si l'utilisateur a epuiser toutes ces devis autorisé :
                if($_SESSION['compte']["user"]->getUser_abonnement()->getNb_devis_restant() > 0){
                    //Si l'utilisateur a encore des devis restant pour le mois :
                    $autorisation = true;
                }else{
                    $autorisation = false;
                }
            }
            
            
            //Si l'autorisation est true du fait qu'il reste des factures ou devis disponible :
            if($autorisation == true){
                $tva_auto_liquidation = false;
                if(isset($_POST['tva_auto_liquidation'])){
                    if($_POST['tva_auto_liquidation'] == "on"){
                        $tva_auto_liquidation = true;
                    }
                }
                //verifier si le numero de facture existe deja dans la base de données
                $resultat = $gst->verifNumFactureDevisExisteByUserId($_POST['numFactureDevis'], $_SESSION['compte']["user"]->getUser_id());
                //Si le numero de facture n'existe pas :
                if(!$resultat){
                    $_SESSION['newFactureDevis'] = array(
                        "numFactureDevis" => $_POST['numFactureDevis'],
                        "type" => $_POST['type'],
                        "dateFacture" => $_POST['dateFacture'],
                        "leVendeur" => $_POST['leVendeur'],
                        "leModel" => $_POST['model'],
                        "tva_auto_liquidation" => $tva_auto_liquidation,
                        "ligneFactureDevis" => []
                    );
                    // $_SESSION['newFactureDevis'] = new Facture_devis(0, $_POST['type'], $_POST['numFactureDevis'], null, $_POST['dateFacture'], )
                    //Redirection
                    //header('Location:detailsNewFacture.php');
                //      ?>
                        <script>window.location.replace("detailsNewFactureDevis.php");</script>
                        <?php
                }
                //Si le numero de facture existe :
                else{
                    ?>
                    <div class="container">
                        <div class="alert alert-danger" role="alert">
                            Le numero de facture/devis "<?php echo $_POST['numFactureDevis']; ?>" existe déja, veuillez en saisir un autre
                        </div>
                    </div>
                    <?php
                }
            }//Fin du if autorisation
            else{
                if($autorisation == false){
                    echo '<div class="alert alert-danger container text-center" role="alert">Vous avez epuisé toutes vos '.$_POST['type'].' pour ce mois </div>';
                }
            }
            
        }//Fin du if(isset)
        ?>
        


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
    <script src="../../javascript/scriptNouvFactDevis"></script>
</body>
</html>