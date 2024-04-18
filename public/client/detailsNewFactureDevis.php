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
}

// var_dump($_SESSION['newFactureDevis']);

//Cette page ne devrais pas etre accessible au client qui ne sont pas abonner
//Pour eviter que les client qui ne sont pas abonner accedent à cette page :
if($_SESSION['compte']["user"]->getUser_abonnement()->getAbo_statut() != "true"){
    ?>
    <script>
        window.alert("Votre abonnement ne vous permet pas d'accéder à cette page");
        window.location.replace("../../deconnexion.php");
    </script>
    <?php
}

$tva_auto_liquidation = $_SESSION['newFactureDevis']['tva_auto_liquidation'];
$lesLignes = $_SESSION['newFactureDevis']['ligneFactureDevis'];
// var_dump($tva_auto_liquidation);

$total_ht = 0;
$total_tva = 0;
foreach ($lesLignes as $laLigne){
  $total_ht = $total_ht + ($laLigne->getQuantite()*$laLigne->getPrix_unitaire_ht());
  // $total_ttc = $total_ttc + ($laLigne['qte']*$laLigne['prix']);
  if(!$tva_auto_liquidation){
    $total_tva = $total_tva + (($laLigne->getQuantite() * $laLigne->getPrix_unitaire_ht()) * ($laLigne->getTva_ligne()/100) );
  }
}
$total_ttc = $total_ht + $total_tva;
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
    <!-- <?php include 'navbar.html'; ?> -->

    <br><br>
    <div class="container">
        <a href="action_pages/action_new_fact_devis/annulerNewFact.php" type="button" class="btn btn-outline-danger">Annuler</a>
        <br><br>
        <p>Vendeur : <?php echo $_SESSION['newFactureDevis']['leVendeur']; ?></p>
        <div class="shadow-sm p-3 mb-5 bg-body rounded">
            <br>
            <h3 class="text-center">Details de votre <?php echo  $_SESSION['newFactureDevis']['type']; ?></h3>
            <br>
        <?php
        for($i = 0; $i < count($lesLignes); $i++){
          ?>
          <div class="shadow-none p-3 mb-5 bg-light rounded text-center">
            <div class="row">
              <div class="col-sm-3">
                <p><?php echo $lesLignes[$i]->getDescription(); ?></p>
                <hr>
              </div>
              <div class="col-sm-2">
                <p><?php echo $lesLignes[$i]->getQuantite(); ?></p>
                <hr>
              </div>
              <div class="col-sm-2">
                <p><?php echo $lesLignes[$i]->getUnite(); ?></p>
                <hr>
              </div>
              <div class="col-sm-3">
                <p><?php echo $lesLignes[$i]->getPrix_unitaire_ht(); ?> €</p>
                <hr>
              </div>
              <?php if(!$tva_auto_liquidation){
                ?>
                <div class="col-sm-2">
                  <p><?php echo $lesLignes[$i]->getTva_ligne();?> %</p>
                  <hr>
                </div>
                <?php
              }?>
            </div>
            <div class="text-center">
                <!-- Button trigger modal -->
                <a type="button" href="action_pages/action_new_fact_devis/supprimerLigneFactDevis.php?ligne=<?php echo $i; ?>"class="btn btn-outline-warning">Supprimer la ligne</a>
            </div>
          </div>
        
          <?php
        }
        ?>
        </div>
        <div class="d-flex justify-content-end">
          <div class="col-4">
          <ul class="list-group">
            <li class="list-group-item">Prix HT : <?php echo $total_ht; ?> €</li>
            <li class="list-group-item">TVA : <?php echo $total_tva; ?> €</li>
            <li class="list-group-item active" aria-current="true">Prix TTC : <?php echo $total_ttc; ?> €</li>
          </ul>
          </div>
        </div>
        <br><br>



        <div class="d-flex justify-content-end">
          <!-- Button trigger modal -->
          <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#ajoutLigne" style="margin:10px">Ajouter une ligne</button>
                  
          <a href="ajouterDestinataire.php" type="button" class="btn btn-outline-success" style="margin:10px">Terminer</a>
                  
        </div>
        
        <!-- Modal -->
        <div class="modal fade" id="ajoutLigne" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" style="color:black;">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Veuillez saisire les informations</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
              <div class="modal-body">
                <form action="action_pages/action_new_fact_devis/ajoutLigneNewFact.php" method="POST">
                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <input type="text" name="desc" class="form-control" Required>
                    </div>
                    <div class="row g-3 align-items-center">
                      <div class="col-auto">
                          <label class="form-label">Quantité</label>
                          <input type="number" name="qte" class="form-control" min="0" Required>
                      </div>
                      <div class="col-auto">
                          <label class="form-label">Unité</label>
                          <input type="text" name="unite" class="form-control" Required>
                      </div>
                      <?php
                      if(!$tva_auto_liquidation){
                        ?>
                        <div class="col-auto">
                            <label class="form-label">TVA (en %)</label>
                            <input type="number" step="0.01" name="tva" class="form-control" Required>
                        </div>
                        <?php
                      }
                      ?>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Prix Hunitaire</label>
                        <input type="number" step="0.01" name="prix"  min="0" class="form-control" Required>
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



    </div><!-- Fin du container principale -->

    
<br><br>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
    </body>
</html>