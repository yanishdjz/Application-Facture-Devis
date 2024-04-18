<?php
class Vendeur
{
    private $id_vendeur;
    private $denomination;
    private $adresse_rue;
    private $adresse_code_ville; 
    private $siret; 
    private $ape; 
    private $tva_intra; 
    private $RIB_NOM; 
    private $RIB_IBAN;
    private $RIB_BIC;
	private $logo;


    public function __construct(int $id_vendeur, string $denomination, string $adresse_rue, string $siret, string $adresse_code_ville, string $ape, string $tva_intra){
		$this->id_vendeur = $id_vendeur;
		$this->denomination = $denomination;
		$this->adresse_rue = $adresse_rue;
		$this->adresse_code_ville = $adresse_code_ville;
		$this->siret = $siret;
		$this->ape = $ape;
		$this->tva_intra = $tva_intra;
    }

    public function getId_vendeur(){
		return $this->id_vendeur;
	}

	public function setId_vendeur($id_vendeur){
		$this->id_vendeur = $id_vendeur;
	}

	public function getDenomination(){
		return $this->denomination;
	}

	public function setDenomination($denomination){
		$this->denomination = $denomination;
	}

	public function getAdresse_rue(){
		return $this->adresse_rue;
	}

	public function setAdresse_rue($adresse_rue){
		$this->adresse_rue = $adresse_rue;
	}

	public function getAdresse_code_ville(){
		return $this->adresse_code_ville;
	}

	public function setAdresse_code_ville($adresse_code_ville){
		$this->adresse_code_ville = $adresse_code_ville;
	}

	public function getSiret(){
		return $this->siret;
	}

	public function setSiret($siret){
		$this->siret = $siret;
	}

	public function getApe(){
		return $this->ape;
	}

	public function setApe($ape){
		$this->ape = $ape;
	}

	public function getTva_intra(){
		return $this->tva_intra;
	}

	public function setTva_intra($tva_intra){
		$this->tva_intra = $tva_intra;
	}

	public function getRIB_NOM(){
		return $this->RIB_NOM;
	}

	public function setRIB_NOM($RIB_NOM){
		$this->RIB_NOM = $RIB_NOM;
	}

	public function getRIB_IBAN(){
		return $this->RIB_IBAN;
	}

	public function setRIB_IBAN($RIB_IBAN){
		$this->RIB_IBAN = $RIB_IBAN;
	}

	public function getRIB_BIC(){
		return $this->RIB_BIC;
	}

	public function setRIB_BIC($RIB_BIC){
		$this->RIB_BIC = $RIB_BIC;
	}

	public function getLogo(){
        return $this->logo;
    }
    public function setLogo(string $logo){
        $this->logo = $logo;
        return $this;
    }




}


?>