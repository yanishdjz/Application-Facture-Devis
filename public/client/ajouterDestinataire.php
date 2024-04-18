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

// var_dump($_SESSION);
$lesDestinataires = $gst->getAllDestinataires($_SESSION['compte']["user"]->getUser_id());
// var_dump($lesDestinataires);


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

    <br><br>
    <div class="container">
       <h4 class="text-center">Veuillez selectionner un destinataire</h4>
       <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#ajoutLigne" style="margin:10px">Ajouter un nouvau Destinataire</button>
       <a type="button" href="detailsNewFactureDevis.php" class="btn btn-outline-danger" style="margin:10px">Retour</a>
        <br>
        <br>
        <div class="list-group text-center">
          <table class="table table-striped table-hover">
            <tbody>
            <?php
            foreach($lesDestinataires as $leDestinataire){
            ?>  
            <tr >        
              <td><a style="color:black;text-decoration:none;" href="action_pages/action_destinataire/useDestinataire.php?id_destinataire=<?php echo $leDestinataire->getDest_id(); ?>"><?php echo $leDestinataire->getDest_denomination(); ?> - <?php echo $leDestinataire->getDest_adresse_rue(); ?>, <?php echo $leDestinataire->getDest_adresse_code_ville(); ?> </a></td>
            </tr>
            <?php
            }
            ?>
            </tbody>
          </table>
        </div>


        



    </div><!-- Fin du container principale -->

        <!-- Modal -->
        <div class="modal fade" id="ajoutLigne" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" style="color:black;">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Veuillez saisire les informations</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
              <div class="modal-body">
                <form action="action_pages/action_destinataire/ajoutNouvDestinataire.php?retour=ajouterDestinataire" method="POST">
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





    
          <br><br>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
    </body>
</html>