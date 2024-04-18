<?php
// require '../../class/Gestionnaire.php';
require  ".." . DIRECTORY_SEPARATOR . ".." . DIRECTORY_SEPARATOR . ".." . DIRECTORY_SEPARATOR . ".." . DIRECTORY_SEPARATOR . 'class' . DIRECTORY_SEPARATOR . 'Gestionnaire.php';
$gst = new Gestionnaire;

session_start();
//Verification de la connexion :
if(!$gst->verifConnexionClient($_SESSION)){
    ?>
    <script>window.location.replace("../../../../deconnexion.php");</script>
    <?php
}

//Verification du model de facture ou devis :
if(isset($_GET['numFactureDevis'])){
  if($_GET['numFactureDevis'] != ""){
    $model = $gst->getModelFactureDevis($_GET['numFactureDevis']);
    if($model != 2){
      ?>
        <script>window.location.replace("../../index.php");</script>
      <?php
    }
  }
}else{
  ?>
    <script>window.location.replace("../../index.php");</script>
  <?php
}

$infos_facture_devis = $gst->getFactureDevisInfos($_SESSION['compte']['user']->getUser_id(), $_GET['numFactureDevis']);
// var_dump($infos_facture_devis);

$les_lignes = $infos_facture_devis->getLesLignes();

$type = $infos_facture_devis->getType();
$num_fact_devis = $infos_facture_devis->getNum_fact_devis();
$date_facture = $infos_facture_devis->getDate_facture();
$tva_auto_liquidation = $infos_facture_devis->getTva_auto_liquidation();
$vendeur_denomination = $infos_facture_devis->getLeVenduer()->getDenomination();
$vendeur_adresse_rue = $infos_facture_devis->getLeVenduer()->getAdresse_rue();
$vendeur_adresse_code_ville = $infos_facture_devis->getLeVenduer()->getAdresse_code_ville();
$vendeur_siret = $infos_facture_devis->getLeVenduer()->getSiret();
$vendeur_ape = $infos_facture_devis->getLeVenduer()->getApe();
$vendeur_tva_intra = $infos_facture_devis->getLeVenduer()->getTva_intra();
$vendeur_rib_nom = $infos_facture_devis->getLeVenduer()->getRIB_NOM();
$vendeur_rib_iban = $infos_facture_devis->getLeVenduer()->getRIB_IBAN();
$vendeur_rib_bic = $infos_facture_devis->getLeVenduer()->getRIB_BIC();
$destinataire_nom_denomination = $infos_facture_devis->getLeDestinataire()->getDest_denomination();
$destinataire_adresse_rue = $infos_facture_devis->getLeDestinataire()->getDest_adresse_rue();
$destinataire_adresse_code_ville = $infos_facture_devis->getLeDestinataire()->getDest_adresse_code_ville();
$destinataire_tva_intra = $infos_facture_devis->getLeDestinataire()->getDest_tvaIntra();
if($infos_facture_devis->getLeVenduer()->getLogo()){
  $vendeur_logo = "../../../../img/all_logo/".$infos_facture_devis->getLeVenduer()->getLogo();
}else{
  $vendeur_logo ="";
}
$totalHT = 0;
$totalTVA = 0;
$totalTTC = 0;
?>
<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">


    <!--[CSS/JS Files - Start]-->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
    <script src="https://cdn.apidelv.com/libs/awesome-functions/awesome-functions.min.js"></script> 
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.9.3/html2pdf.bundle.min.js" ></script>

 
    <script type="text/javascript">
    $(document).ready(function($) 
    { 

      $(document).on('click', '.btn_print', function(event) 
      {
        event.preventDefault();

        //credit : https://ekoopmans.github.io/html2pdf.js

        var element = document.getElementById('container_content'); 
        var opt = {
          background: '#FAF1DE',
          image:        { type: 'jpeg', quality: 0.98 },
          html2canvas:  { scale: 2 },
          jsPDF:        { unit: 'in', format: 'letter', orientation: 'portrait' }
        };
        //Sauvegarde
        html2pdf().set(opt).from(element).save();

      });
    });
    </script>
    <title>pdf</title>
  </head>
  <body>
<br><br>

<div class="text-center" style="padding:20px;">
    <input type="button" value="Télécharger" class="btn btn-outline-primary btn_print">
    <a href="../../index.php" type="button" class="btn btn-outline-danger">Retour</a>
</div>



    <div class="container shadow-lg p-3 mb-5  rounded" style="color:#404553;" id="container_content">
      <div style="background-color:#404553; padding-top:20px;">.</div>
      <br><br>
      <div class="container">
        <div class="row">
          <div class="col-6">
          <?php
            if($vendeur_logo != ""){
              ?>
              <div class="row">
                <div class="col-5">
                  <img src="<?php echo $vendeur_logo; ?>" width="140"><br><br>
                </div>
                <div class="col">
                  <h1 style="font-family:times"><b><?php echo $vendeur_denomination; ?></b></h1>
                </div>
              </div>
              <?php
            }else{
              echo '<h1 style="font-family:times"><b>'.$vendeur_denomination.'</b></h1>';
            }
          ?>
              <!-- <h1 style="font-family:times"><b><?php echo $vendeur_denomination; ?></b></h1> -->
              <h5 class="fw-lighter"><?php echo $vendeur_adresse_rue; ?><br> 750016 Paris<h5>
              <br><br>
              <div class="row">
                <div class="col-4">
                  <h6 class="text-end">SIRET :</h6>
                  <h6 class="text-end">APE :</h6>
                  <h6 class="text-end">TVA INTRA :</h6>
                </div>
                <div class="col-8">
                  <h6 class="fw-lighter"><?php echo $vendeur_siret; ?></h6>
                  <h6 class="fw-lighter"><?php echo $vendeur_ape; ?></h6>
                  <h6 class="fw-lighter"><?php echo $vendeur_tva_intra; ?></h6>
                </div>
              </div>
          </div>
          <div class="col-6">
            <h1 class="text-center" style="font-family:times;text-transform:capitalize"><b><?php echo $type; ?></b></h1>
            <div class="row">
              <div class="col-6">
                <h6 class="text-end"><b>DATE :</b></h6>
                <h6 class="text-end"><b>N° :</b></h6>
              </div>
              <div class="col-6">
                <h6><?php echo date("d/m/Y", strtotime($date_facture)); ?></h6>
                <h6><?php echo $num_fact_devis; ?></h6>
              </div>
            </div>
            <br><br>
            <div class="shadow-none p-3 mb-5 bg-light rounded" style="padding-right:100%">
              <p><b><?php echo $destinataire_nom_denomination; ?></b></p>
              <p><?php echo $destinataire_adresse_rue; ?> <br> <?php echo $destinataire_adresse_code_ville; ?></p>
            </div>
          </div>
        </div>
      </div>
      
<div style="margin:50px">
  <table class="table">
      
      <?php
      if($tva_auto_liquidation == true){
        ?>
        <thead style="background:#404553; color:white">
          <tr>
            <th scope="col">DÉSCRIPTION</th>
            <th scope="col">Qté</th>
            <th scope="col">Unité</th>
            <th scope="col">PRIX H.T</th>
            <th scope="col">TOTAL</th>
          </tr>
        </thead>
        <tbody>
          <?php
          foreach($les_lignes as $laLigne){
            $total_ligne = ($laLigne->getQuantite() * $laLigne->getPrix_unitaire_ht());
            ?>
            <tr>
              <td><?php echo $laLigne->getDescription(); ?></td>
              <td><?php echo $laLigne->getQuantite(); ?></td>
              <td><?php echo $laLigne->getUnite(); ?></td>
              <td><?php echo number_format($laLigne->getPrix_unitaire_ht(), 2, ',', ' '); ?> €</td>
              <td><?php echo number_format($total_ligne, 2, ',', ' '); ?> €</td>
            </tr>
            <?php
            $totalHT = $totalHT + ($laLigne->getQuantite()*$laLigne->getPrix_unitaire_ht());
          }
          ?>
        </tbody>
      <?php
        
      }else if($tva_auto_liquidation == false){
        ?>
        <thead style="background:#404553; color:white">
          <tr>
            <th scope="col">DÉSCRIPTION</th>
            <th scope="col">Qté</th>
            <th scope="col">Unité</th>
            <th scope="col">PRIX H.T</th>
            <th scope="col">TVA</th>
            <th scope="col">TOTAL</th>
          </tr>
        </thead>
        <tbody>
          <?php
          foreach($les_lignes as $laLigne){
            $total_ligne = ($laLigne->getQuantite() * $laLigne->getPrix_unitaire_ht()) + ($laLigne->getQuantite() * $laLigne->getPrix_unitaire_ht() * ($laLigne->getTva_ligne()/100));
            ?>
            <tr>
              <td><?php echo $laLigne->getDescription(); ?></td>
              <td><?php echo $laLigne->getQuantite(); ?></td>
              <td><?php echo $laLigne->getUnite(); ?></td>
              <td><?php echo number_format($laLigne->getPrix_unitaire_ht(), 2, ',', ' '); ?> €</td>
              <td><?php echo number_format($laLigne->getTva_ligne(), 2, ',', ' '); ?> %</td>
              <td><?php echo number_format($total_ligne, 2, ',', ' '); ?> €</td>
            </tr>
            <?php
            $totalHT =  $totalHT + ($laLigne->getQuantite()*$laLigne->getPrix_unitaire_ht());
            $totalTVA = $totalTVA + ($laLigne->getQuantite() * $laLigne->getPrix_unitaire_ht()) * ($laLigne->getTva_ligne()/100);
          }
          ?>
        </tbody>
  
  
      <?php
      }//fermeture du else
      ?>
        
  
  
  
  
      </table>
      <br>
      <div class="row justify-content-end">
        <div class="col-5">
        <table class="table table-bordered border-secondary">
          <tbody>
            <tr>
              <td style="background:#404553; color:white">TOTAL H.T</td>
              <td class="text-end"><?php echo number_format($totalHT, 2, ',', ' '); ?> €</td>
            </tr>
            <tr>
              <td style="background:#404553; color:white">TVA</td>
              <td class="text-end">
                <?php if($totalTVA == 0){
                  echo "-";
                }else{
                  echo number_format($totalTVA, 2, ',', ' ')." €";
                } ?>
              </td>
            </tr>
            <tr>
              <td style="background:#404553; color:white">TOTAL TTC</td>
              <td class="text-end"><?php echo number_format($totalTTC = $totalHT + $totalTVA, 2, ',', ' '); ?> €</td>
            </tr>
          </tbody>
        </table>
  
        </div>
  
  
  
  
      </div>
  
     
      <?php
      if($tva_auto_liquidation == "true"){
        echo "<br>";
        echo '<p class="text-center">TVA Autoliquidation, article 283 du Code Général des Impôts</p>';
      }
      ?>
    </div>







    </div>
















    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>

  </body>
</html>