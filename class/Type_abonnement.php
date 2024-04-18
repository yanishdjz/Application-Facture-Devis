<?php
class Type_abonnement
{
    private $id; //int
    private $nom; //String
    private $nb_max_facture; //int
    private $nb_max_devis; //int
    private $prix_abo;


    public function __construct(int $id, string $nom, int $nb_max_facture, int $nb_max_devis, float $prix_abo){
        $this->id = $id;
        $this->nom = $nom;
        $this->nb_max_facture = $nb_max_facture;
        $this->nb_max_devis = $nb_max_devis;
        $this->prix_abo = $prix_abo;
    }


	public function getId(){
		return $this->id;
	}

	public function setId($id){
		$this->id = $id;
	}

	public function getNom(){
		return $this->nom;
	}

	public function setNom($nom){
		$this->nom = $nom;
	}

	public function getNb_max_facture(){
		return $this->nb_max_facture;
	}

	public function setNb_max_facture($nb_max_facture){
		$this->nb_max_facture = $nb_max_facture;
	}

	public function getNb_max_devis(){
		return $this->nb_max_devis;
	}

	public function setNb_max_devis($nb_max_devis){
		$this->nb_max_devis = $nb_max_devis;
	}

	public function getPrix_abo(){
		return $this->prix_abo;
	}

	public function setPrix_abo($prix_abo){
		$this->prix_abo = $prix_abo;
	}






}
?>