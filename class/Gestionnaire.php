<?php
require dirname(__DIR__) . DIRECTORY_SEPARATOR . 'class' . DIRECTORY_SEPARATOR . 'Bdd.php';
require dirname(__DIR__) . DIRECTORY_SEPARATOR . 'class' . DIRECTORY_SEPARATOR . 'User.php';
require dirname(__DIR__) . DIRECTORY_SEPARATOR . 'class' . DIRECTORY_SEPARATOR . 'Facture_devis.php';
require dirname(__DIR__) . DIRECTORY_SEPARATOR . 'class' . DIRECTORY_SEPARATOR . 'Abonnement.php';

class Gestionnaire
{
    //Fonction utiliser pour se connecter 
    //Si l'utilisateur exist : renvoie l'utilisateur
    //Si l'utilisateur n'existe pas : renvoie false et un tableau vide 
    public function getConnexion($login, $mdp){
        $bdd = new Bdd();
        $accesConnexion = array(
            "connexion" => false,
            "user" => null,
        );

        $rqt = 'SELECT user.id AS user_id, user.identifiant, user.statut, abonnement.id AS abo_id, abonnement.abo_statut, abonnement.date_abonnement, abonnement.date_fin, abonnement.nb_fact_restant, abonnement.nb_devis_restant, type_abonnement.id AS id_type_abo, type_abonnement.nom, type_abonnement.nb_max_facture, type_abonnement.nb_max_devis, type_abonnement.prix_abo FROM user LEFT JOIN abonnement ON user.id = abonnement.num_user LEFT JOIN type_abonnement ON abonnement.num_type_abo = type_abonnement.id WHERE user.identifiant = ? AND user.mdp = ?;';
        $lesValeurs = array(strtolower($login),md5($mdp));//strtolower() permet de mettre tous le string en minuscule
        $resultat = $bdd->rqtProteger($rqt, $lesValeurs);
        // var_dump($resultat);
        if($resultat){
            // $leTyepAbonnement = new Type_abonnement();
            // $lAbonnement = new Abonnement();
            $leUser = new User($resultat[0]['user_id'], $resultat[0]['identifiant'], $resultat[0]['statut']);
            if($resultat[0]['abo_id'] != ""){
                $letypeAbo = new Type_abonnement($resultat[0]['id_type_abo'], $resultat[0]['nom'], $resultat[0]['nb_max_facture'], $resultat[0]['nb_max_devis'], (float)$resultat[0]['prix_abo']);
                $lAbonnement = new Abonnement($resultat[0]['abo_id'], filter_var($resultat[0]['abo_statut'], FILTER_VALIDATE_BOOLEAN), $resultat[0]['date_abonnement'], $resultat[0]['date_fin'], $letypeAbo, $resultat[0]['nb_fact_restant'], $resultat[0]['nb_devis_restant']);
                $leUser->setUser_abonnement($lAbonnement);
            }

            $accesConnexion["connexion"] = true;
            $accesConnexion["user"] = $leUser;
        }
        // var_dump($accesConnexion);
        return $accesConnexion;
    }

    //Fonction utiliser pour verifier si l'utilisateur est connecter
    //si l'utilisateur est connecter : verfie si le type d'utilisateur est le bon
    //la fonction doit recuperer la session entiere
    public function verifConnexionClient($laSession){
        $etreConnecter = false;
        if(isset($laSession['compte'])){//si la session existe
            if($laSession['compte']['connexion']){//si la varriable de session 'connexion' est true
                if($laSession['compte']['user']->getUser_statut() == "client"){ //si la varriable de session 'statut' est bien le client
                    $etreConnecter = true;
                }
            }
        }
        return $etreConnecter;
    } 
    
    //Fonction utiliser pour verifier si l'utilisateur est connecter
    //si l'utilisateur est connecter : verfie si le type d'utilisateur est le bon
    //la fonction doit recuperer la session entiere
    public function verifConnexionAdmin($laSession){
        $etreConnecter = false;
        if(isset($laSession['compte'])){//si la session existe
            if($laSession['compte']['connexion']){//si la varriable de session 'connexion' est true
                if($laSession['compte']['user']->getUser_statut() == "admin"){ //si la varriable de session 'statut' est bien le admin
                    $etreConnecter = true;
                }
            }
        }
        return $etreConnecter;
    } 

    //Recuperer le statut de l'abonnement d'un utilisateur par son Id
    //Met à jour les variable abo_statut et date_fin par rapport à la base de données
    public function updateAbonnementByUserId($user_id){
        $rqt = "SELECT abonnement.id, abonnement.num_user, abonnement.abo_statut, abonnement.date_abonnement, abonnement.date_fin, abonnement.nb_fact_restant, abonnement.nb_devis_restant, type_abonnement.id AS num_type_abo, type_abonnement.nom, type_abonnement.nb_max_facture, type_abonnement.nb_max_devis, type_abonnement.prix_abo FROM abonnement INNER JOIN type_abonnement ON abonnement.num_type_abo = type_abonnement.id WHERE abonnement.num_user = ?; CALL proc_maj_abo(?);";
        $bdd = new Bdd();
        $lesValeurs = array($user_id, $user_id);
        $resultat = $bdd->rqtProteger($rqt, $lesValeurs);
        // var_dump($_SESSION['compte']['user']->getUser_abonnement());
        if($resultat){
            if($_SESSION['compte']['user']->getUser_abonnement()){
                $_SESSION['compte']['user']->getUser_abonnement()->setAbo_statut(filter_var($resultat[0]['abo_statut'], FILTER_VALIDATE_BOOLEAN));
                $_SESSION['compte']['user']->getUser_abonnement()->setDate_fin($resultat[0]['date_fin']);
                $leTypeAbo = new type_abonnement($resultat[0]['num_type_abo'], $resultat[0]['nom'], $resultat[0]['nb_max_facture'], $resultat[0]['nb_max_devis'], $resultat[0]['prix_abo']);
                $_SESSION['compte']['user']->getUser_abonnement()->setLeTypeAbonnement($leTypeAbo);
            }else{
                $leTypeAbo = new type_abonnement($resultat[0]['num_type_abo'], $resultat[0]['nom'], $resultat[0]['nb_max_facture'], $resultat[0]['nb_max_devis'], $resultat[0]['prix_abo']);
                $lAbonnement = new Abonnement($resultat[0]['id'], filter_var($resultat[0]['abo_statut'], FILTER_VALIDATE_BOOLEAN), $resultat[0]['date_abonnement'], $resultat[0]['date_fin'], $leTypeAbo, $resultat[0]['nb_fact_restant'], $resultat[0]['nb_devis_restant']);
                $_SESSION['compte']['user']->setUser_abonnement($lAbonnement);
            }
        }

    }



    //Recuperer tous les factures par l'id de l'utilsateur
    public function getAllFacturesByUserId($id_user){
        $resultat = [];
        $rqt = "SELECT facture_devis.id, facture_devis.num_fact_devis, facture_devis.num_destinataire, facture_devis.date_facture, facture_devis.total_ttc, facture_devis.num_vendeur, facture_devis.model_facture_devis, facture_devis.tva_auto_liquidation, destinataire.id_destinataire,  destinataire.nom_denomination AS dest_nom, destinataire.adresse_rue AS dest_adresse_rue, destinataire.adresse_code_ville AS dest_adresse_code_ville, destinataire.tva_intra AS dest_tva_intra, vendeur.logo AS vend_logo, vendeur.id_vendeur, vendeur.denomination AS ven_nom, vendeur.adresse_rue AS ven_adresse_rue, vendeur.adresse_code_ville AS ven_adresse_code_ville, vendeur.siret AS ven_siret, vendeur.ape AS ven_ape, vendeur.tva_intra AS ven_tva_intra, vendeur.RIB_NOM AS ven_RIB_NOM, vendeur.RIB_IBAN AS ven_RIB_IBAN, vendeur.RIB_BIC AS ven_RIB_BIC FROM facture_devis INNER JOIN destinataire ON facture_devis.num_destinataire = destinataire.id_destinataire INNER JOIN vendeur ON facture_devis.num_vendeur = vendeur.id_vendeur WHERE facture_devis.num_user = ? AND facture_devis.type = 'facture';";
        $bdd = new Bdd();
        $lesValeurs = array($id_user,);
        $lesFactures = $bdd->rqtProteger($rqt, $lesValeurs);
        //Pour chaque facture :
        foreach($lesFactures as $laFacture){
            //Mettre les factures dans un tableau :
            $leVendeur = new Vendeur($laFacture['id_vendeur'], $laFacture['ven_nom'], $laFacture['ven_adresse_rue'], $laFacture['ven_adresse_code_ville'], $laFacture['ven_siret'], $laFacture['ven_ape'], $laFacture['ven_tva_intra']);
            if($laFacture['ven_RIB_NOM'] != "" && $laFacture['ven_RIB_IBAN'] != "" && $laFacture['ven_RIB_BIC'] != ""){
                $leVendeur->setRIB_NOM($laFacture['ven_RIB_NOM']);
                $leVendeur->setRIB_IBAN($laFacture['ven_RIB_IBAN']);
                $leVendeur->setRIB_BIC($laFacture['ven_RIB_BIC']);
            }
            $leDestinataire = new Destinataire($laFacture['id_destinataire'], $laFacture['dest_nom'], $laFacture['dest_adresse_rue'], $laFacture['dest_adresse_code_ville']);
            if($laFacture['dest_tva_intra'] != ""){
                $leDestinataire->setDest_tvaIntra($laFacture['dest_tva_intra']);
            }
            if($laFacture['vend_logo'] != ""){
                $leVendeur->setLogo($laFacture['vend_logo']);
            }
            $resultat[] = new Facture_devis($laFacture['id'], "facture", $laFacture['num_fact_devis'], $leDestinataire, $laFacture['date_facture'], (float)$laFacture['total_ttc'], $leVendeur, $laFacture['model_facture_devis'], $laFacture['tva_auto_liquidation'] );
            
        }
        //Retour du tableau :
        return $resultat; 
    }

    //Recuperer tous les devis par l'id de l'utilsateur
    public function getAllDevisByUserId($id_user){
        $resultat = [];
        $rqt = "SELECT facture_devis.id, facture_devis.num_fact_devis, facture_devis.num_destinataire, facture_devis.date_facture, facture_devis.total_ttc, facture_devis.num_vendeur, facture_devis.model_facture_devis, facture_devis.tva_auto_liquidation, destinataire.id_destinataire,  destinataire.nom_denomination AS dest_nom, destinataire.adresse_rue AS dest_adresse_rue, destinataire.adresse_code_ville AS dest_adresse_code_ville, destinataire.tva_intra AS dest_tva_intra, vendeur.logo AS vend_logo, vendeur.id_vendeur, vendeur.denomination AS ven_nom, vendeur.adresse_rue AS ven_adresse_rue, vendeur.adresse_code_ville AS ven_adresse_code_ville, vendeur.siret AS ven_siret, vendeur.ape AS ven_ape, vendeur.tva_intra AS ven_tva_intra, vendeur.RIB_NOM AS ven_RIB_NOM, vendeur.RIB_IBAN AS ven_RIB_IBAN, vendeur.RIB_BIC AS ven_RIB_BIC FROM facture_devis INNER JOIN destinataire ON facture_devis.num_destinataire = destinataire.id_destinataire INNER JOIN vendeur ON facture_devis.num_vendeur = vendeur.id_vendeur WHERE facture_devis.num_user = ? AND facture_devis.type = 'devis';";
        $bdd = new Bdd();
        $lesValeurs = array($id_user,);
        $lesFactures = $bdd->rqtProteger($rqt, $lesValeurs);
        //Pour chaque facture :
        foreach($lesFactures as $laFacture){
            //Mettre les factures dans un tableau :
            $leVendeur = new Vendeur($laFacture['id_vendeur'], $laFacture['ven_nom'], $laFacture['ven_adresse_rue'], $laFacture['ven_adresse_code_ville'], $laFacture['ven_siret'], $laFacture['ven_ape'], $laFacture['ven_tva_intra']);
            if($laFacture['ven_RIB_NOM'] != "" && $laFacture['ven_RIB_IBAN'] != "" && $laFacture['ven_RIB_BIC'] != ""){
                $leVendeur->setRIB_NOM($laFacture['ven_RIB_NOM']);
                $leVendeur->setRIB_IBAN($laFacture['ven_RIB_IBAN']);
                $leVendeur->setRIB_BIC($laFacture['ven_RIB_BIC']);
            }
            $leDestinataire = new Destinataire($laFacture['id_destinataire'], $laFacture['dest_nom'], $laFacture['dest_adresse_rue'], $laFacture['dest_adresse_code_ville']);
            if($laFacture['dest_tva_intra'] != ""){
                $leDestinataire->setDest_tvaIntra($laFacture['dest_tva_intra']);
            }
            if($laFacture['vend_logo'] != ""){
                $leVendeur->setLogo($laFacture['vend_logo']);
            }
            $resultat[] = new Facture_devis($laFacture['id'], "facture", $laFacture['num_fact_devis'], $leDestinataire, $laFacture['date_facture'], (float)$laFacture['total_ttc'], $leVendeur, $laFacture['model_facture_devis'], $laFacture['tva_auto_liquidation'] );
            
        }
        //Retour du tableau :
        return $resultat; 
    }

    //verifier si le numero de facture existe deja dans la base de données
    //retourne true si existe et false s'il n'existe pas
    public function verifNumFactureDevisExisteByUserId($num_facture_devis, $user_id){
        $retour = false;
        $rqt = "SELECT facture_devis.num_fact_devis FROM facture_devis WHERE facture_devis.num_fact_devis = ? AND facture_devis.num_user = ?;";
        $bdd = new Bdd();
        $lesValeurs = array($num_facture_devis, $user_id);
        $resultat = $bdd->rqtProteger($rqt, $lesValeurs);
        if($resultat){
            $retour = true;
        }

        return $retour;
    }

    //Fonction pour supprimer soit une facture soit un devis
    public function suppFactureDevis($fact_id, $user_id){
        $rqt = "DELETE FROM `ligne_facture_devis` WHERE ligne_facture_devis.num_facture = ? ;DELETE FROM `facture_devis` WHERE facture_devis.id = ? ;";
        $bdd = new Bdd();
        $lesValeurs = array($fact_id, $fact_id);
        $resultat = $bdd->rqtProtegerSansReturn($rqt, $lesValeurs);
        
    }

    //Fonction permettant la convertion d'un devis en facture
    public function convertFactureToDevisById($devis_id){
        $rqt = "UPDATE facture_devis SET facture_devis.type = 'facture' WHERE facture_devis.id = ?;";
        $bdd = new Bdd();
        $lesValeurs = array($devis_id);
        $resultat = $bdd->rqtProtegerSansReturn($rqt, $lesValeurs);
    }

    //Recupere les destinataire dans la table acheteur_vendeur dons le le num proprietaire est l'utilisateur connecter
    public function getAllDestinataires($user_id){
        $lesDestinataires = array();
        $rqt = "SELECT `id_destinataire`, `nom_denomination`, `adresse_rue`, `adresse_code_ville`, `tva_intra` FROM `destinataire` WHERE destinataire.num_user_proprietaire = ?;";
        $bdd = new Bdd();
        $lesValeurs = array($user_id,);
        $resultats = $bdd->rqtProteger($rqt, $lesValeurs);

        foreach ($resultats as $leResultat){
            $leDest = new Destinataire($leResultat['id_destinataire'], $leResultat['nom_denomination'], $leResultat['adresse_rue'], $leResultat['adresse_code_ville']);
            if($leResultat['tva_intra'] != ""){
                $leDest->setDest_tvaIntra($laFacture['tva_intra']);
            }
            $lesDestinataires[] = $leDest;
        }

        return $lesDestinataires; 
    }




    //Fonction permettant l'ajout d'une facture dans la base de données
    //Cette fonction recupere l'id de l'utilisateur, puis le numero de facture ou devis, puis le type(facture ou devis), 
    //puis la date, puis un tableau contenant toutes les lignes et l'id du destinataire
    public function ajouterFactureDevis($user_id, $num_Fact_Devis, $type, $date, $lesLignes, $destinataire_id, $id_vendeur, $model, $tva_auto_liquidation){
        $rqt = "INSERT INTO `facture_devis`(`id`, `num_user`, `type`, `num_fact_devis`, `num_destinataire`, `date_facture`, `total_ttc`, `num_vendeur`, `model_facture_devis`, `tva_auto_liquidation` ) VALUES (NULL,?,?,?,?,?,?,?,?,?)";
        $bdd = new Bdd();
        $total_ttc = 0;
        foreach ($lesLignes as $laLigne){
            $total_ttc = $total_ttc + ($laLigne->getQuantite()*$laLigne->getPrix_unitaire_ht());
        }
        $lesValeurs_devis_facture = array($user_id, $type, $num_Fact_Devis, $destinataire_id, $date, $total_ttc, $id_vendeur, $model, $tva_auto_liquidation);
        $resultat = $bdd->rqtProtegerSansReturn($rqt, $lesValeurs_devis_facture);
        
    }

    public function ajouterLignes($num_Fact_Devis, $user_id, $lesLignes){
        //ilfaut d'abord recuperer l'id de la facture ou du devis inserer grace au numero de facture et l'id de l'utilisateur 
        $rqt = "SELECT facture_devis.id FROM facture_devis WHERE facture_devis.num_user = ? AND facture_devis.num_fact_devis = ?;";
        $bdd = new Bdd();
        $lesValeurs = array($user_id, $num_Fact_Devis);
        $id_facture = $bdd->rqtProteger($rqt, $lesValeurs);

        //Pour chaque ligne de la facture faire une requete d'insertion :
        foreach ($lesLignes as $laLigne){
            $rqtLigne = "INSERT INTO ligne_facture_devis (`id_ligne`, `num_facture`, `description`, `quantite`, `unité`, `prix_unitaire_ht`, `tva_ligne`) VALUES (NULL, ?, ?, ?, ?, ?, ?);";
            $lesValeurs_ligne = array($id_facture[0]['id'], $laLigne->getDescription(), $laLigne->getQuantite(), $laLigne->getUnite(), $laLigne->getPrix_unitaire_ht(), $laLigne->getTva_ligne());
            $bdd->rqtProtegerSansReturn($rqtLigne, $lesValeurs_ligne);
        }
        // return $id_facture[0]['id'];
    }

    //Ajouter un destinataire dans la table destinataire
    public function ajouterNewDestinataire($user_id, $nom_denomination, $adresse_rue, $adresse_code_ville, $tva_intra){
        $rqt = "INSERT INTO `destinataire` (`id_destinataire`, `num_user_proprietaire`, `nom_denomination`, `adresse_rue`, `adresse_code_ville`, `tva_intra`) VALUES (NULL, ?, ?, ?, ?, ?);";
        $bdd = new Bdd();
        $lesValeurs = array($user_id, $nom_denomination, $adresse_rue, $adresse_code_ville, $tva_intra);
        $resultat = $bdd->rqtProtegerSansReturn($rqt, $lesValeurs);
    }


    //Ajouter un Vendeur dans la table vendeur :
    public function ajouterNewVendeur($user_id, $nom_denomination, $adresse_rue, $adresse_code_ville, $siret, $ape, $tva_intra, $RIB_NOM, $RIB_IBAN, $RIB_BIC, $logo){
        $rqt = "INSERT INTO `vendeur`(`id_vendeur`, `num_user_proprietaire`, `denomination`, `adresse_rue`, `adresse_code_ville`, `siret`, `ape`, `tva_intra`, `RIB_NOM`, `RIB_IBAN`, `RIB_BIC`, `logo`) VALUES (NULL,?,?,?,?,?,?,?,?,?,?,?)";
        $bdd = new Bdd();
        $lesValeurs = array($user_id, $nom_denomination, $adresse_rue, $adresse_code_ville, $siret, $ape, $tva_intra, $RIB_NOM, $RIB_IBAN, $RIB_BIC, $logo);
        $resultat = $bdd->rqtProtegerSansReturn($rqt, $lesValeurs);
    }


    //Supprime un destinataire dans la table destinataire
    //nous utilisons l'id de la ligne ainsi que l'id utilisateur pour ajouter une couche de securité
    public function suppDestinataire($id_destinataire, $user_id){
        $rqt = "DELETE FROM destinataire WHERE destinataire.id_destinataire = ? AND destinataire.num_user_proprietaire = ?;";
        $bdd = new Bdd();
        $lesValeurs = array($id_destinataire ,$user_id);
        $resultat = $bdd->rqtProtegerSansReturn($rqt, $lesValeurs);
    }
    
    //Supprime un vendeur dans la table vendeur
    //nous utilisons l'id de la ligne ainsi que l'id utilisateur pour ajouter une couche de securité
    public function suppVendeur($id_vendeur, $user_id){
        $rqt = "DELETE FROM `vendeur` WHERE vendeur.id_vendeur = ? AND vendeur.num_user_proprietaire = ?;";
        $bdd = new Bdd();
        $lesValeurs = array($id_vendeur, $user_id);
        $resultat = $bdd->rqtProtegerSansReturn($rqt, $lesValeurs);
    }



    Public function getAllVendeurByIdUser($user_id){
        $rqt = "SELECT `id_vendeur`, `denomination` FROM `vendeur` WHERE vendeur.num_user_proprietaire = ?;";
        $bdd = new Bdd();
        $lesValeurs = array($user_id,);
        $resultat = $bdd->rqtProteger($rqt, $lesValeurs);
        return $resultat;
    }

    public function getModelFactureDevis($facture_devis_id){
        $rqt = "SELECT `model_facture_devis` FROM `facture_devis` WHERE id = ?;";
        $bdd = new Bdd();
        $lesValeurs = array($facture_devis_id,);
        $resultat = $bdd->rqtProteger($rqt, $lesValeurs);
        return $resultat[0]['model_facture_devis'];
    }

    public function getFactureDevisInfos($user_id, $facture_devis_id){
        $rqtFactureDevisInfos = "SELECT facture_devis.id, facture_devis.type, facture_devis.num_fact_devis, facture_devis.date_facture, facture_devis.total_ttc, facture_devis.tva_auto_liquidation, facture_devis.model_facture_devis, vendeur.id_vendeur, vendeur.denomination AS vendeur_denomination, vendeur.adresse_rue AS vendeur_adresse_rue, vendeur.adresse_code_ville AS vendeur_adresse_code_ville, vendeur.siret AS vendeur_siret, vendeur.ape AS vendeur_ape, vendeur.tva_intra AS vendeur_tva_intra, vendeur.RIB_NOM AS vendeur_rib_nom, vendeur.RIB_IBAN AS vendeur_rib_iban, vendeur.RIB_BIC AS vendeur_rib_bic, vendeur.logo, destinataire.id_destinataire, destinataire.nom_denomination AS destinataire_nom_denomination, destinataire.adresse_rue AS destinataire_adresse_rue, destinataire.adresse_code_ville AS destinataire_adresse_code_ville, destinataire.tva_intra AS destinataire_tva_intra FROM facture_devis INNER JOIN vendeur ON facture_devis.num_vendeur = vendeur.id_vendeur INNER JOIN destinataire ON facture_devis.num_destinataire = destinataire.id_destinataire WHERE facture_devis.num_user = ? AND facture_devis.id = ?;";
        $bdd = new Bdd();
        $lesValeurs = array($user_id, $facture_devis_id);
        $infosFactureDevis = $bdd->rqtProteger($rqtFactureDevisInfos, $lesValeurs);

        $rqtLignes = "SELECT `description`, `quantite`, `unité`, `prix_unitaire_ht`, `tva_ligne` FROM `ligne_facture_devis` WHERE ligne_facture_devis.num_facture = ?;";
        $lesValeurs_ligne = array($facture_devis_id,);
        $lesLignes = $bdd->rqtProteger($rqtLignes, $lesValeurs_ligne);

        
        $infos_lignes = [];
        foreach ($lesLignes as $laLigne){
            $infos_lignes[] = new Ligne_facture_devis($laLigne['description'], (int)$laLigne['quantite'], $laLigne['unité'], (float)$laLigne['prix_unitaire_ht']);
        }

        $resultat = null;
        if($infosFactureDevis){
            $leVendeur = new Vendeur((int)$infosFactureDevis[0]['id_vendeur'], $infosFactureDevis[0]['vendeur_denomination'], $infosFactureDevis[0]['vendeur_adresse_rue'], $infosFactureDevis[0]['vendeur_siret'], $infosFactureDevis[0]['vendeur_adresse_code_ville'], $infosFactureDevis[0]['vendeur_ape'], $infosFactureDevis[0]['vendeur_tva_intra']);
            $leDestinataire = new Destinataire((int)$infosFactureDevis[0]['id_destinataire'], $infosFactureDevis[0]['destinataire_nom_denomination'], $infosFactureDevis[0]['destinataire_adresse_rue'], $infosFactureDevis[0]['destinataire_adresse_code_ville']);
            if($infosFactureDevis[0]['destinataire_tva_intra'] != ""){
                $leDestinataire->setDest_tvaIntra($infosFactureDevis['destinataire_tva_intra']);
            }
            $resultat = new Facture_devis( (int)$infosFactureDevis[0]['id'], $infosFactureDevis[0]['type'], $infosFactureDevis[0]['num_fact_devis'], $leDestinataire, $infosFactureDevis[0]['date_facture'], (float)$infosFactureDevis[0]['total_ttc'], $leVendeur, (int)$infosFactureDevis[0]['model_facture_devis'], (bool)$infosFactureDevis[0]['tva_auto_liquidation']);
            if($infosFactureDevis[0]['logo'] != ""){
                $resultat->getLeVenduer()->setLogo($infosFactureDevis[0]['logo']);
            }
            $resultat->setLesLignes($infos_lignes);
        }

        return $resultat;
    }

    //Cette fonction est utiliser avant la suppression d'un destinatiare pour eviter les erreurs en BDD
    public function verifDestinaireUtiliser($num_destinataire){
        $retour = false;
        $bdd = new Bdd();
        $rqt = 'SELECT facture_devis.id FROM facture_devis WHERE facture_devis.num_destinataire = ?;';
        $lesValeurs = array($num_destinataire,);
        $resultat = $bdd->rqtProteger($rqt, $lesValeurs);
        if($resultat){
            $retour = true;
        }
        return $retour;
    }


    //Cette fonction permet de verifier si un vendeur est utiliser par un devis ou facture
    //Cette fonction est utiliser avant la suppression d'un destinatiare pour eviter les erreurs en BDD
    public function verifVendeurUtiliser($num_vendeur){
        $retour = false;
        $bdd = new Bdd();
        $rqt = 'SELECT facture_devis.id FROM facture_devis WHERE facture_devis.num_vendeur = ?;';
        $lesValeurs = array($num_vendeur,);
        $resultat = $bdd->rqtProteger($rqt, $lesValeurs);
        if($resultat){
            $retour = true;
        }
        return $retour;
    }

    //Recupe le nombre de facture restant pour l'utilisateur :
    public function getNbFacturesByUserId($user_id){
        $nb_facture_restant = 0;
        $bdd = new Bdd();
        $rqt = 'SELECT `nb_fact_restant` FROM `abonnement` WHERE abonnement.num_user = ?;';
        $lesValeurs = array($user_id,);
        $resultat = $bdd->rqtProteger($rqt, $lesValeurs);
        if($resultat){
            $nb_facture_restant = $resultat[0]['nb_fact_restant'];
        }
        return $nb_facture_restant;
    }

    //Recuperer le nombre de devis restant pour l'utilsateur :
    public function getNbDevisByUserId($user_id){
        $nb_devis_restant = 0;
        $bdd = new Bdd();
        $rqt = 'SELECT `nb_devis_restant` FROM `abonnement` WHERE abonnement.num_user = ?;';
        $lesValeurs = array($user_id,);
        $resultat = $bdd->rqtProteger($rqt, $lesValeurs);
        if($resultat){
            $nb_devis_restant = $resultat[0]['nb_devis_restant'];
        }
        return $nb_devis_restant;
    }

    //Recuperer les information de l'utilisateur par son id :
    public function getUserInofsById($user_id){
        $bdd = new Bdd();
        $rqt = 'SELECT user.identifiant, user.mail, abonnement.abo_statut, abonnement.date_fin, type_abonnement.nom AS abo_nom, type_abonnement.nb_max_facture, type_abonnement.nb_max_devis FROM user LEFT JOIN abonnement ON user.id = abonnement.num_user LEFT JOIN type_abonnement ON abonnement.num_type_abo = type_abonnement.id WHERE user.id = ?;';
        $lesValeurs = array($user_id,);
        $resultat = $bdd->rqtProteger($rqt, $lesValeurs);
        $le_resultat = [
            "identifiant" => $resultat[0]['identifiant'],
            "mail" => $resultat[0]['mail'],
            "abo_statut" => $resultat[0]['abo_statut'],
            "abo_nom" => $resultat[0]['abo_nom'],
            "nb_max_facture" => $resultat[0]['nb_max_facture'],
            "nb_max_devis" => $resultat[0]['nb_max_devis'],
            "date_fin_abo" => $resultat[0]['date_fin']
        ];

        return $le_resultat;
    }

    //Verifier un mots de passe :
    public function verifMdpByUserId($user_id, $mdp){
        $retour = false;
        $bdd = new Bdd();
        $rqt = 'SELECT user.id FROM user WHERE user.id = ? AND user.mdp = ?;';
        $lesValeurs = array($user_id,md5($mdp));
        $resultat = $bdd->rqtProteger($rqt, $lesValeurs);
        
        //Si le mots de passe est bon
        if($resultat){
            $retour = true;
        }
        return $retour;
    }

    //Changer le mots de passe d'un utilisateur :
    public function changerMdp($user_id, $new_mdp){
        $rqt = "UPDATE `user` SET `mdp` = ? WHERE `user`.`id` = ?;";
        $bdd = new Bdd();
        $lesValeurs = array(md5($new_mdp), $user_id);
        $resultat = $bdd->rqtProtegerSansReturn($rqt, $lesValeurs);
    }

    //Changer le mail d'un utilisateur :
    public function changerMail($user_id, $new_mail){
        $rqt = "UPDATE `user` SET `mail` = ? WHERE `user`.`id` = ?;";
        $bdd = new Bdd();
        $lesValeurs = array($new_mail, $user_id);
        $resultat = $bdd->rqtProtegerSansReturn($rqt, $lesValeurs);
    }

    //Chnger l'identifiant d'un utilisateur :
    public function changerIdent($user_id, $new_id){
        $rqt = "UPDATE `user` SET `identifiant` = ? WHERE `user`.`id` = ?;";
        $bdd = new Bdd();
        $lesValeurs = array($new_id, $user_id);
        $resultat = $bdd->rqtProtegerSansReturn($rqt, $lesValeurs);
    }


    //Recuperer tous les type d'abonnement :
    public function getAllTypeAbonnements(){
        $rqt = "SELECT `id`, `nom`, `nb_max_facture`, `nb_max_devis`, `prix_abo` FROM `type_abonnement`;";
        $bdd = new Bdd();
        $resultat = $bdd->requeteBdd($rqt);
        return $resultat;
    }

    //Recuperer les nom d'un forfait par son id :
    public function getInfosForfaitById($id_forfait){
        $bdd = new Bdd();
        $rqt = 'SELECT `id`, `nom`, `nb_max_facture`, `nb_max_devis`, `prix_abo` FROM `type_abonnement` WHERE `id` = ?;';
        $lesValeurs = array($id_forfait);
        $resultat = $bdd->rqtProteger($rqt, $lesValeurs);
        return $resultat[0];
    }

    
    public function inscriptionNewClient($userId, $mdp, $mail){
        $rqt = "INSERT INTO `user`(`id`, `identifiant`, `mdp`, `statut`, `mail`) VALUES (NULL,?,?,'client',?);";
        $bdd = new Bdd();
        $lesValeurs = array($userId, md5($mdp), $mail);
        $resultat = $bdd->rqtProtegerSansReturn($rqt, $lesValeurs);
    }






    //------------------------------------------Fonctions de test------------------------------------------
    //Fonctions destiner a tester l'application
    //Fonctions non existant sur l'application finalisé:
    public function abonnement($id_user, $num_abonnement){
        $gst = new Gestionnaire();
        $infosAbonnement = $gst->getInfosForfaitById($num_abonnement);
        //$rqt est composé de 2 requette :
        //La premiere sert à ajouter l'abonnement dans la table abonnement mais la date_fin de l'abonnement sera la date du jour de l'abonnement, pour régler se probleme il y a la deuxieme requette
        //La deuxieme sert à ajouter 31 jours sur la colone date_fin
        $rqt = "INSERT INTO `abonnement`(`id`, `num_user`, `abo_statut`, `date_abonnement`, `date_fin`, `num_type_abo`, `nb_fact_restant`, `nb_devis_restant`) VALUES (null,?,'true',CURRENT_DATE(),CURRENT_DATE(),?,?,?); UPDATE `abonnement` SET `date_fin` = DATE_ADD(`date_fin` , INTERVAL 31 DAY) WHERE `num_user` = ?;";
        $bdd = new Bdd();
        $lesValeurs = array($id_user, $num_abonnement, $infosAbonnement['nb_max_facture'], $infosAbonnement['nb_max_devis'], $id_user);
        $resultat = $bdd->rqtProtegerSansReturn($rqt, $lesValeurs);
        
    }





}



?>