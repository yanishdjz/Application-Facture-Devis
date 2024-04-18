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

//Cette fonction permet de mettre à jour les données d'abonnement du client à chaque rafrechissement :
$gst->updateAbonnementByUserId($_SESSION['compte']["user"]->getUser_id());

$infos_user = $gst->getUserInofsById($_SESSION['compte']["user"]->getUser_id());
$nb_facture_restant = $gst->getNbFacturesByUserId($_SESSION['compte']["user"]->getUser_id());
$nb_devis_restant = $gst->getNbDevisByUserId($_SESSION['compte']["user"]->getUser_id());
// var_dump($infos_user);



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
                <h1>ESPACE MON COMPTE</h1>
                <hr>
            </div>

            <div class="row">
                <div class="col-sm-6">
                    <div class="shadow-none p-3 mb-5 bg-light rounded">
                        <h5>Information de votre compte</h5>
                        <hr>
                        <p>
                            Votre identifiant de connexion : <?php echo $infos_user['identifiant']; ?>
                            <br>
                            Votre mail de recuperation : <?php echo $infos_user['mail']; ?>
                        </p>
                        <button type="button" class="btn btn-outline-danger" data-bs-toggle="modal" data-bs-target="#changer_mdp">Changer de mots de passe</button>
                        <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#changer_mail">Changer de mail</button>
                        <button type="button" class="btn btn-outline-dark" data-bs-toggle="modal" data-bs-target="#changer_id">Changer d'identifiant</button>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="shadow-none p-3 mb-5 bg-light rounded">
                        <h5>Abonnement Actuel</h5>
                        <hr>
                        <p>
                            <?php
                            if($infos_user['abo_statut'] != null){
                                if($infos_user['abo_statut'] != 'true'){
                                    echo "Votre abonnement à pris fin le : ".date("d/m/Y", strtotime($infos_user['date_fin_abo']));
                                }else{
                                    ?>
                                    Formule : <span style="text-transform: uppercase;"><?php echo $infos_user['abo_nom']; ?> </span><a href="#" data-bs-toggle="modal" data-bs-target="#infos_forfait">Plus d'infos</a>
                                    <br>
                                    Nombre de facture restant : <?php echo $nb_facture_restant; ?>
                                    <br>
                                    Nombre de devis restant : <?php echo $nb_devis_restant; ?>
                                    <br>
                                    Votre abonnement prend fin le : <?php echo date("d/m/Y", strtotime($infos_user['date_fin_abo']));
                                }
                            }else{
                                echo "Vous n'êtes pas abonné.";
                            }
                            ?>
                            
                        </p>
                    </div>
                </div>
            </div>
        </div><!-- Fin du container principal -->
        <br><br>


        <!-- Modal infos forfait-->
        <div class="modal fade" id="infos_forfait" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Formule <?php echo $infos_user['abo_nom']; ?></h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p>
                            Nombre de facture maximal par mois : <?php echo $infos_user['nb_max_facture']; ?>
                            <br>
                            Nombre de Devis maximal par mois : <?php echo $infos_user['nb_max_devis']; ?>
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal changer mots de passe -->
        <div class="modal fade" id="changer_mdp" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Changer de mots de passe</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form action="" method="POST">
                            <div class="mb-3">
                                <label class="form-label">Encien mots de passe : </label>
                                <input name="old_mdp" type="password" class="form-control" autocomplete="off" Required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Nouveau mots de passe : </label>
                                <input name="mdp1" type="password" class="form-control" autocomplete="off" Required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Confirmer : </label>
                                <input name="mdp2" type="password" class="form-control" autocomplete="off" Required>
                            </div>
                            <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Annuler</button>
                            <button type="submit" class="btn btn-outline-success">Valider</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>


        <!-- Modal changer mail -->
        <div class="modal fade" id="changer_mail" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Changer de mail</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form action="" method="POST">
                            <div class="mb-3">
                                <label class="form-label">Nouveau mail : </label>
                                <input name="new_mail" type="email" class="form-control" autocomplete="off" Required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Votre mots de passe : </label>
                                <input name="mdp_confirm_mail" type="password" class="form-control" autocomplete="off" Required>
                            </div>
                            <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Annuler</button>
                            <button type="submit" class="btn btn-outline-success">Valider</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal changer id -->
        <div class="modal fade" id="changer_id" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Changer l'identifiant</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form action="" method="POST">
                            <div class="mb-3">
                                <label class="form-label">Nouvelle identifiant : </label>
                                <input name="new_id" type="text" class="form-control" autocomplete="off" Required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Votre mots de passe : </label>
                                <input name="mdp_confirm_id" type="password" class="form-control" autocomplete="off" Required>
                            </div>
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

//Changement de mots de passe :
if(isset($_POST['old_mdp']) && isset($_POST['mdp1']) && isset($_POST['mdp2'])){
    if($_POST['mdp1'] != $_POST['mdp2']){
        //Si les 2 mdp de confirmation sont differant
        $message = "Le mots de passe de confirmation n'est pas identique au mots de passe de confirmation";
    }else{
        if($gst->verifMdpByUserId($_SESSION['compte']["user"]->getUser_id(),$_POST['old_mdp']) == true){
            //Changer le mots de passe :
            $gst->changerMdp($_SESSION['compte']["user"]->getUser_id(), $_POST['mdp1']);
            //Une fois le mots de passe changer, supprimer les variable POST :
            $message = "Votre mots de passe a bien été changer";

        }else{
            $message = "L'encien mots de passe est incorrect";
        }
    }
}

//Changement de mail :
if(isset($_POST['new_mail']) && isset($_POST['mdp_confirm_mail'])){
    if($gst->verifMdpByUserId($_SESSION['compte']["user"]->getUser_id(),$_POST['mdp_confirm_mail']) == true){
        //Changer le mots de passe :
        $gst->changerMail($_SESSION['compte']["user"]->getUser_id(), $_POST['new_mail']);
        //Une fois le mots de passe changer, supprimer les variable POST :
        $message = "Votre mail a bien été changer";

    }else{
        $message = "Le mots de passe est incorrect";
    }
}

//Chnager d'identifiant :
if(isset($_POST['new_id']) && isset($_POST['mdp_confirm_id'])){
    if($gst->verifMdpByUserId($_SESSION['compte']["user"]->getUser_id(),$_POST['mdp_confirm_id']) == true){
        //Changer le mots de passe :
        $gst->changerIdent($_SESSION['compte']["user"]->getUser_id(), $_POST['new_id']);
        //Une fois le mots de passe changer, supprimer les variable POST :
        $message = "Votre identifiant a bien été changer";
        
    }else{
        $message = "Le mots de passe est incorrect";
    }
}


//la varraible $message permettra d'afficher les messages souhaité en cas de besoin
if(isset($message)){
    echo '<script>alert("'.$message.'");</script>';
    echo '<script>window.location.replace("mon_compte.php");</script>';
}
?>