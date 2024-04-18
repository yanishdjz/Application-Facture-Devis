<?php
class Destinataire
{
    private $dest_id;
    private $dest_denomination;
    private $dest_adresse_rue;
    private $dest_adresse_code_ville;
    private $dest_tvaIntra;

    public function __construct(int $dest_id, string $dest_denomination, string $dest_adresse_rue, string $dest_adresse_code_ville){
        $this->dest_id = $dest_id;
        $this->dest_denomination = $dest_denomination;
        $this->dest_adresse_rue = $dest_adresse_rue;
        $this->dest_adresse_code_ville = $dest_adresse_code_ville;
    }


    public function getDest_id(){
        return $this->dest_id;
    }
    public function setDest_id($dest_id){
        $this->dest_id = $dest_id;
        return $this;
    }

    public function getDest_denomination(){
        return $this->dest_denomination;
    }
    public function setDest_denomination($dest_denomination){
        $this->dest_denomination = $dest_denomination;
        return $this;
    }

    public function getDest_adresse_rue(){
        return $this->dest_adresse_rue;
    }
    public function setDest_adresse_rue($dest_adresse_rue){
        $this->dest_adresse_rue = $dest_adresse_rue;
        return $this;
    }

    public function getDest_adresse_code_ville(){
        return $this->dest_adresse_code_ville;
    }
    public function setDest_adresse_code_ville($dest_adresse_code_ville){
        $this->dest_adresse_code_ville = $dest_adresse_code_ville;
        return $this;
    }

    public function getDest_tvaIntra(){
        return $this->dest_tvaIntra;
    }
    public function setDest_tvaIntra($dest_tvaIntra){
        $this->dest_tvaIntra = $dest_tvaIntra;
        return $this;
    }



}

?>