<?php
require 'Type_abonnement.php';

class Abonnement
{
    private $id; //int
    private $num_user; //String
    private $abo_statut; //Bool
    private $date_abonnement; //Date
    private $date_fin; //Date
    private $leTypeAbonnement; //Type_abonnement
    private $nb_fact_restant; //int
    private $nb_devis_restant; //int


    public function __construct(int $id, bool $abo_statut, String $date_abonnement, String $date_fin, Type_abonnement $leTypeAbonnement, int $nb_fact_restant, int $nb_devis_restant){
        $this->id = $id;
        $this->abo_statut = $abo_statut;
        $this->date_abonnement = $date_abonnement;
        $this->date_fin = $date_fin;
        $this->leTypeAbonnement = $leTypeAbonnement;
        $this->nb_fact_restant = $nb_fact_restant;
        $this->nb_devis_restant = $nb_devis_restant;
    }


    public function getId() {
        return $this->id;
    }
    public function setId(int $id){
        $this->id = $id;
        return $this;
    }

    public function getNum_user(){
        return $this->num_user;
    }
    public function setNum_user(int $num_user){
        $this->num_user = $num_user;
        return $this;
    }

    public function getAbo_statut(){
        return $this->abo_statut;
    }
    public function setAbo_statut(bool $abo_statut){
        $this->abo_statut = $abo_statut;
        return $this;
    }

    public function getDate_abonnement(){
        return $this->date_abonnement;
    }
    public function setDate_abonnement(String $date_abonnement){
        $this->date_abonnement = $date_abonnement;
        return $this;
    }

    public function getDate_fin(){
        return $this->date_fin;
    }
    public function setDate_fin(String $date_fin){
        $this->date_fin = $date_fin;
        return $this;
    }

    public function getLeTypeAbonnement(){
        return $this->leTypeAbonnement;
    }
    public function setLeTypeAbonnement(Type_abonnement $leTypeAbonnement){
        $this->leTypeAbonnement = $leTypeAbonnement;
        return $this;
    }

    public function getNb_fact_restant(){
        return $this->nb_fact_restant;
    }
    public function setNb_fact_restant(int $nb_fact_restant){
        $this->nb_fact_restant = $nb_fact_restant;
        return $this;
    }

    public function getNb_devis_restant(){
        return $this->nb_devis_restant;
    }
    public function setNb_devis_restant(int $nb_devis_restant){
        $this->nb_devis_restant = $nb_devis_restant;
        return $this;
    }



}


?>