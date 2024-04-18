<?php
// require '../../class/Gestionnaire.php';
require ".." . DIRECTORY_SEPARATOR .".." . DIRECTORY_SEPARATOR .".." . DIRECTORY_SEPARATOR . ".." . DIRECTORY_SEPARATOR . 'class' . DIRECTORY_SEPARATOR . 'Gestionnaire.php';
session_start();
$gst = new Gestionnaire;

//Verification de la connexion :
if(!$gst->verifConnexionClient($_SESSION)){
    ?>
    <script>window.location.replace("../../../../deconnexion.php");
        //window.alert("BOnjour");
    </script>
    <?php
}
else{
//Cette page est la premiere etape de suppression qui consiste a verrifier si l'tulisateur veut vraiment supprimer la facture
    if(isset($_GET['numFactureDevis'])){
        if($_GET['numFactureDevis'] != ""){
            // echo $_GET['numFactureDevis'];
            if(isset($_GET['confirm'])){
                if($_GET['confirm'] == "oui"){
                    $gst->suppFactureDevis($_GET['numFactureDevis'], $_SESSION['compte']["user"]->getUser_id());
                    ?>
                    <script>window.location.replace("../../index.php");</script>
                    <?php
                }
            }else{
                ?>
                <script>
                    // console.log("b");
                    if (confirm("Etes vous sur de bien vouloir la supprimer") == true) {
                        //Si l'ulisateur confirme la suppression : ajout d'une varrible get confirm dons la valeur est oui
                        window.location.replace("supp_fact_devis.php?numFactureDevis=<?php echo $_GET['numFactureDevis']; ?>&confirm=oui");
                    } else {
                        //Si l'utilisateur ne confirme pas la suppression : il est rediriger vers la page acceuil
                        window.location.replace("../../index.php");
                    }
                </script>
                <?php
            }
        }
        else{
            //header('Location:../newFacture/clearSessionFacture.php');
            ?>
            <script>window.location.replace("../../../../deconnexion.php");</script>
            <?php
        }
    }
    else{
        //header('Location:../newFacture/clearSessionFacture.php');
        ?>
        <script>window.location.replace("../../../../deconnexion.php");</script>
        <?php
    }
}





?>
