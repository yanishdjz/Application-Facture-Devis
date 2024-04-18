<?php
// require '../../class/Gestionnaire.php';
require ".." . DIRECTORY_SEPARATOR . ".." . DIRECTORY_SEPARATOR . 'class' . DIRECTORY_SEPARATOR . 'Gestionnaire.php';
$gst = new Gestionnaire;

session_start();
// var_dump($_SESSION);

//Verification de la connexion :
if(!$gst->verifConnexionClient($_SESSION)){
    ?>
    <script>window.location.replace("../../deconnexion.php");</script>
    <?php
}else{
    if(isset($_GET['forfait'])){
        if($_GET['forfait'] == ""){
            echo "Veuillez patienter, la convertion de votre devis est en cours...";
            //header('Location:../newFacture/clearSessionFacture.php');
            ?>
            <script>window.location.replace("../../deconnexion.php");</script>
            <?php
            
        }
    }else{
        //header('Location:../newFacture/clearSessionFacture.php');
        ?>
        <script>window.location.replace("../../deconnexion.php");</script>
        <?php
    }
}
$infos_forfait = $gst->getInfosForfaitById($_GET['forfait']);
// var_dump($infos_forfait);
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
        <div class="titre">
            <h1>Bienvenue sur la plateforme de paiement</h1>
            <hr>
        </div>
        <br>
        <h5 class="text-center">Vous avez choisie le forfait <?php echo $infos_forfait['nom']; ?></h5>
        <div class="container">
            <div class="shadow-none p-3 mb-5 bg-light rounded">
                <h6 class="text-center">Contenue du pack</h6>
                <p class="text-center">
                    <?php echo $infos_forfait['nb_max_facture']; ?> Factures par mois
                    <br>
                    <?php echo $infos_forfait['nb_max_devis']; ?> Devis par mois
                </p>
            </div>
        </div>
        <br>
        <div class="container text-center">
            <p>Choisissez votre mode de paiement</p>
            <button type="button" class="btn btn-outline-dark" data-bs-toggle="modal" data-bs-target="#paiement_cb">Payer par carte</button>
            <!-- <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#paiement_paypal">Par PayPal</button> -->
            
        </div>

        <!-- Modal changer mots de passe -->
        <div class="modal fade" id="paiement_cb" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Information de votre carte</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <h4 class="text-center">Paiement de <?php echo $infos_forfait['prix_abo']; ?>€</h4>
                        <form action="" method="POST">
                            <label class="form-label">Selectionner le type de carte</label>
                            <select name="type" class="form-select" aria-label="Default select example" Required>
                                <option value="1">Master Card</option>
                                <option value="2">Visa</option>
                            </select>
                            <br>
                            <div class="row">
                                <div class="col-6">
                                    <label class="form-label">Nom</label>
                                    <input name="nom" type="text" class="form-control" autocomplete="off" Required>
                                </div>
                                <div class="col-6">
                                    <label class="form-label">Prenom</label>
                                    <input name="prenom" type="text" class="form-control" autocomplete="off" Required>
                                </div>
                            </div>
                            <br>
                            <div class="mb-3">
                                <label class="form-label">Numero de carte</label>
                                <input name="numero" type="text" class="form-control" autocomplete="off" Required>
                            </div>
                            <div class="row">
                                <div class="col-8">
                                    <label class="form-label">Date</label>
                                    <input name="date" type="date" class="form-control" autocomplete="off" Required>
                                </div>
                                <div class="col-4">
                                    <label class="form-label">Crypto</label>
                                    <input name="crypto" type="password" class="form-control" autocomplete="off" Required>
                                </div>
                            </div>
                            <br>
                            <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Annuler</button>
                            <button type="submit" class="btn btn-outline-success">Valider</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>




    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>


</body>
</html>

<?php
// var_dump($infos_forfait);
if(isset($_POST['type']) && isset($_POST['nom']) && isset($_POST['prenom']) && isset($_POST['numero']) && isset($_POST['date']) && isset($_POST['crypto'])){

    $gst->abonnement($_SESSION['compte']["user"]->getUser_id(), $infos_forfait['id']);
    echo '<script>window.location.replace("mon_abonnement.php");</script>';
}else{
    //$message = "Votre saisie n'est pas complete";
}


//la varraible $message permettra d'afficher les messages souhaité en cas de besoin
if(isset($message)){
    echo '<script>alert("'.$message.'");</script>';
    echo '<script>window.location.replace("paiement.php");</script>';
}
?>