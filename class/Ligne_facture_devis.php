<?php
class Ligne_facture_devis
{
    private $id_ligne;
    private $description;
    private $quantite;
    private $unite;
    private $prix_unitaire_ht;
    private $tva_ligne;

    public function __construct(string $description, int $quantite, string $unite, float $prix_unitaire_ht){
		$this->description = $description;
		$this->quantite = $quantite;
		$this->unite = $unite;
		$this->prix_unitaire_ht = $prix_unitaire_ht;
    }

    public function getId_ligne(){
		return $this->id_ligne;
	}

	public function setId_ligne($id_ligne){
		$this->id_ligne = $id_ligne;
	}

	public function getDescription(){
		return $this->description;
	}

	public function setDescription($description){
		$this->description = $description;
	}

	public function getQuantite(){
		return $this->quantite;
	}

	public function setQuantite($quantite){
		$this->quantite = $quantite;
	}

	public function getUnite(){
		return $this->unite;
	}

	public function setUnite($unite){
		$this->unite = $unite;
	}

	public function getPrix_unitaire_ht(){
		return $this->prix_unitaire_ht;
	}

	public function setPrix_unitaire_ht($prix_unitaire_ht){
		$this->prix_unitaire_ht = $prix_unitaire_ht;
	}

	public function getTva_ligne(){
		return $this->tva_ligne;
	}

	public function setTva_ligne($tva_ligne){
		$this->tva_ligne = $tva_ligne;
	}


}
?>