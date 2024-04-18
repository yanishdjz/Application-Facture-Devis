<?php
// require '../../class/Gestionnaire.php';
require ".." . DIRECTORY_SEPARATOR . ".." . DIRECTORY_SEPARATOR . ".." . DIRECTORY_SEPARATOR . ".." . DIRECTORY_SEPARATOR . 'class' . DIRECTORY_SEPARATOR . 'Gestionnaire.php';
$gst = new Gestionnaire;

session_start();
//Verification de la connexion :
if(!$gst->verifConnexionClient($_SESSION)){
    ?>
    <script>window.location.replace("../../../../deconnexion.php");</script>
    <?php
}
// var_dump();

if(isset($_POST["raison_sociale"]) && isset($_POST["adresse_rue"]) && isset($_POST["adresse_code"]) && isset($_POST["adresse_ville"]) && isset($_POST["siret"]) && isset($_POST["ape"]) && isset($_POST["num_tva_intra"])){
    
    //Si le code TVA INTRA n'existe pas :
    if($_POST["num_tva_intra"] == ""){
        $_POST["num_tva_intra"] = null;
    }
    //Si le rib_nom n'existe pas :
    if($_POST["rib_nom"] == ""){
        $_POST["rib_nom"] = null;
    }
    //Si le rib_iban n'existe pas :
    if($_POST["rib_iban"] == ""){
        $_POST["rib_iban"] = null;
    }
    //Si le rib_bic n'existe pas :
    if($_POST["rib_bic"] == ""){
        $_POST["rib_bic"] = null;
    }

    //Ressembler dans la meme variable le code postale ainsi que la ville :
    $adresse_code_ville = $_POST["adresse_code"]." ".$_POST["adresse_ville"];
    
    //Partie ajout du logo :
    $theLogo = null;
    if(isset($_FILES["logo"]) AND !empty($_FILES['logo']['name'])){
        $tailleMax = 2097152; //defnir la taille max du fichier en ko
        $extensionsValides = array('jpg', 'jpeg', 'gif', 'png');
        if($_FILES['logo']['size'] <= $tailleMax) {
            $extensionUpload = strtolower(substr(strrchr($_FILES['logo']['name'], '.'), 1));
            if(in_array($extensionUpload, $extensionsValides)) {
    
                $cheminUpload = $_SESSION['compte']["user"]->getUser_id().rand(5, 15).".".$extensionUpload;
                $resultat = move_uploaded_file($_FILES['logo']['tmp_name'], "../../../../img/all_logo/".$cheminUpload);
                if($resultat) {
                //si tous est bon :
                    $theLogo = $cheminUpload;
                } else {
                    echo "Erreur durant l'importation de votre logo";
                }
            } else {
                echo "Votre logo doit être au format jpg, jpeg, gif ou png";
            }
        } else {
            echo "Votre logo ne doit pas dépasser 2Mo";
        }
    }





    //Fonction d'ajout dans la Base de données : 
    $gst->ajouterNewVendeur($_SESSION['compte']['user']->getUser_id(), $_POST["raison_sociale"], $_POST["adresse_rue"], $adresse_code_ville, $_POST["siret"], $_POST["ape"], $_POST["num_tva_intra"], $_POST["rib_nom"], $_POST["rib_iban"], $_POST["rib_bic"], $theLogo);
    //Pour savoir sur quel page faire la redirection nous prenons un varriable passer en GET
    if(isset($_GET['retour'])){
        if($_GET['retour'] == "gerer_clients_vendeurs"){
            ?>
            <script>window.location.replace("../../gerer_clients_vendeurs.php");</script>
            <?php
        }

    }else{
        ?>
            <script>window.location.replace("../../gerer_clients_vendeurs.php");</script>
        <?php
    }
    
}


?>