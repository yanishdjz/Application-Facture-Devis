<?php
require 'Destinataire.php';
require 'Vendeur.php';
require 'Ligne_facture_devis.php';


class Facture_devis
{
    private $id; //int
    private $type; //String
    private $num_fact_devis; //String
    private $leDestinataire; //Destinataire
    private $date_facture; //Date
    private $total_ttc; //double
    private $leVenduer; //Vendeur
    private $model_facture_devis; //int
    private $tva_auto_liquidation; //String
	private $lesLignes;

    public function __construct(int $id, String $type, string $num_fact_devis, Destinataire $leDestinataire, string $date_facture, float $total_ttc, Vendeur $leVenduer,int $model_facture_devis, bool $tva_auto_liquidation){
        $this->id = $id;
		$this->type = $type;
		$this->num_fact_devis = $num_fact_devis;
		$this->leDestinataire = $leDestinataire;
		$this->date_facture = $date_facture;
		$this->total_ttc = $total_ttc;
		$this->leVenduer = $leVenduer;
		$this->model_facture_devis = $model_facture_devis;
		$this->tva_auto_liquidation = $tva_auto_liquidation;

    }

    public function getId(){
		return $this->id;
	}

	public function setId($id){
		$this->id = $id;
	}

	public function getType(){
		return $this->type;
	}

	public function setType($type){
		$this->type = $type;
	}

	public function getNum_fact_devis(){
		return $this->num_fact_devis;
	}

	public function setNum_fact_devis($num_fact_devis){
		$this->num_fact_devis = $num_fact_devis;
	}

	public function getLeDestinataire(){
		return $this->leDestinataire;
	}

	public function setLeDestinataire(Destinataire $leDestinataire){
		$this->leDestinataire = $leDestinataire;
	}

	public function getDate_facture(){
		return $this->date_facture;
	}

	public function setDate_facture($date_facture){
		$this->date_facture = $date_facture;
	}

	public function getTotal_ttc(){
		return $this->total_ttc;
	}

	public function setTotal_ttc($total_ttc){
		$this->total_ttc = $total_ttc;
	}

	public function getLeVenduer(){
		return $this->leVenduer;
	}

	public function setLeVenduer(Vendeur $leVenduer){
		$this->leVenduer = $leVenduer;
	}

	public function getModel_facture_devis(){
		return $this->model_facture_devis;
	}

	public function setModel_facture_devis($model_facture_devis){
		$this->model_facture_devis = $model_facture_devis;
	}

	public function getTva_auto_liquidation(){
		return $this->tva_auto_liquidation;
	}

	public function setTva_auto_liquidation($tva_auto_liquidation){
		$this->tva_auto_liquidation = $tva_auto_liquidation;
	}

	public function getLesLignes(){
		return $this->lesLignes;
	}

	public function setLesLignes(array $lesLignes){
		$this->lesLignes = $lesLignes;
	}



}



?>