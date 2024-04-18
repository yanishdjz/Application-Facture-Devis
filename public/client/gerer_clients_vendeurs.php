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
// var_dump($_SESSION);

$lesVendeurs = $gst->getAllVendeurByIdUser($_SESSION['compte']["user"]->getUser_id());

$lesDestinataires = $gst->getAllDestinataires($_SESSION['compte']["user"]->getUser_id());

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
            <h1>Espace de Gestion</h1>
            <hr>
        </div>


        <div class="container">
            <div class="row">
                <div class="col-sm-6">
                    <div class="shadow-sm p-3 mb-5 bg-body rounded">
                        <h3 class="text-center">Les Vendeurs</h3>
                        <hr>
                        <div class="text-end">
                            <button type="button" class="btn btn-outline-dark" data-bs-toggle="modal" data-bs-target="#nouvVendeur" style="margin:10px">Ajouter un nouvau Vendeur</button>
                        </div>
                        <br>
                        <div class="list-group">
                            <?php
                            if(count($lesVendeurs) < 1){
                                echo '<p class="text-center">Vous n\'avez pas de vendeur enregistrer </p>';
                            }else{
                                ?>
                                <table class="table table-striped table-hover">
                                    <tbody>
                                    <?php
                                    foreach ($lesVendeurs as $leVendeur){
                                        ?>
                                        <tr>        
                                            <td><a style="color:black;text-decoration:none;" ><?php echo $leVendeur['denomination']; ?></a></td>
                                            <td class="text-end"><a style="color:red;text-decoration:none;" href="action_pages/action_vendeur/suppVendeur.php?id_vendeur=<?php echo $leVendeur['id_vendeur']; ?>&retour=gerer_clients_vendeurs">Supprimer</a></td>
                                        </tr>
                                        <?php
                                    }
                                    ?>
                                    </tbody>
                                </table>
                                <?php
                            }
                            ?>
                        </div>
                    </div>

                </div>
                <div class="col-sm-6">
                    <div class="shadow-sm p-3 mb-5 bg-body rounded">
                        <h3 class="text-center">Les Destinataires</h3>
                        <hr>
                        <div class="text-end">
                            <button type="button" class="btn btn-outline-dark" data-bs-toggle="modal" data-bs-target="#nouvDestinataire" style="margin:10px">Ajouter un nouvau Destinataire</button>
                        </div>
                        <br>
                        <div class="list-group">
                            <?php
                            if(count($lesDestinataires) < 1){
                                echo '<p class="text-center">Vous n\'avez pas de destinataire enregistrer </p>';
                            }else{
                                ?>
                                <table class="table table-striped table-hover">
                                    <tbody>
                                    <?php
                                    foreach($lesDestinataires as $leDestinataire){
                                    ?>  
                                    <tr>        
                                    <td><a style="color:black;text-decoration:none;"><?php echo $leDestinataire->getDest_denomination(); ?> - <?php echo $leDestinataire->getDest_adresse_rue(); ?>, <?php echo $leDestinataire->getDest_adresse_code_ville(); ?> </a></td>
                                    <td><a style="color:red;text-decoration:none;" href="action_pages/action_destinataire/suppDestinataire.php?id_destinataire=<?php echo $leDestinataire->getDest_id(); ?>&retour=gerer_clients_vendeurs">Supprimer</a></td>
                                    </tr>
                                    <?php
                                    }
                                    ?>
                                    </tbody>
                                </table>
                                <?php
                            }
                            ?>
                        </div>
                        

                    </div>

                </div>
            </div>
        </div>


        <!-- Modal Vendeur -->
        <div class="modal fade" id="nouvVendeur" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" style="color:black;">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Veuillez saisire les informations</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
              <div class="modal-body">
                <form action="action_pages/action_vendeur/ajoutNouvVendeur.php?retour=gerer_clients_vendeurs" method="POST" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label class="form-label">Raison Sociale ou Nom *</label>
                        <input type="text" name="raison_sociale" class="form-control" autocomplete="off" Required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Adresse *</label>
                        <input type="text" name="adresse_rue" class="form-control" autocomplete="off" Required>
                    </div>
                    <div class="row g-3 align-items-center">
                      <div class="col-auto">
                          <label class="form-label">Code Postale *</label>
                          <input type="number" name="adresse_code" class="form-control" autocomplete="off" Required>
                      </div>
                      <div class="col-auto">
                          <label class="form-label">Ville *</label>
                          <input type="text" name="adresse_ville" class="form-control" autocomplete="off" Required>
                      </div>
                    </div>
                    <hr>
                    <div class="row g-3 align-items-center">
                      <div class="col-auto">
                            <label class="form-label">Siret *</label>
                            <input type="text" name="siret" class="form-control" autocomplete="off" minlength="14" Required>
                      </div>
                      <div class="col-auto">
                          <label class="form-label">Code APE *</label>
                          <input type="text" name="ape" class="form-control" minlength="5" autocomplete="off" Required>
                      </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Num TVA Intra *</label>
                        <input type="text" name="num_tva_intra" class="form-control" autocomplete="off" Required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Logo</label>
                        <input type="file" name="logo" class="form-control">
                    </div>
                    <p><small><i>* Champs Obligatoire</i></small></p>
                    <hr>
                    <h5>Information bancaire</h5>
                    <p><small><i>(Optionnel)</i></small></p>
                    <div class="mb-3">
                        <label class="form-label">Titulaire du Compte</label>
                        <input type="text" name="rib_nom" class="form-control" autocomplete="off">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">IBAN</label>
                        <input type="text" name="rib_iban" class="form-control" autocomplete="off">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">BIC</label>
                        <input type="text" name="rib_bic" class="form-control" autocomplete="off">
                    </div>
                    
                    <div class="text-center">
                        <button type="submit" class="btn btn-success">Ajouter</button>
                    </div>
                </form>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Annuler</button>
              </div>
            </div>
          </div>
        </div>



        <!-- Modal Destinataire-->
        <div class="modal fade" id="nouvDestinataire" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" style="color:black;">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Veuillez saisire les informations</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
              <div class="modal-body">
                <form action="action_pages/action_destinataire/ajoutNouvDestinataire.php?retour=gerer_clients_vendeurs" method="POST">
                    <div class="mb-3">
                        <label class="form-label">Raison Sociale ou Nom *</label>
                        <input type="text" name="raison_sociale" class="form-control" autocomplete="off" Required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Adresse *</label>
                        <input type="text" name="adresse_rue" class="form-control" autocomplete="off" Required>
                    </div>
                    <div class="row g-3 align-items-center">
                      <div class="col-auto">
                          <label class="form-label">Code Postale *</label>
                          <input type="number" name="adresse_code" class="form-control" autocomplete="off" Required>
                      </div>
                      <div class="col-auto">
                          <label class="form-label">Ville *</label>
                          <input type="text" name="adresse_ville" class="form-control" autocomplete="off" Required>
                      </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Num TVA Intra</label>
                        <input type="text" name="num_tva_intra" class="form-control" autocomplete="off">
                    </div>
                    <p><small><i>* Champs Obligatoire</i></small></p>
                    <div class="text-center">
                        <button type="submit" class="btn btn-success">Ajouter</button>
                    </div>
                </form>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Annuler</button>
              </div>
            </div>
          </div>
        </div>



    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
    </body>
</html>