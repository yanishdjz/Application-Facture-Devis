<?php
class User
{
    private $user_id;
    private $user_login;
    private $user_mdp;
    private $user_statut;
    private $user_abonnement;

    public function __construct(int $user_id, string $user_login, string $user_statut){
        $this->user_id = $user_id;
        $this->user_login = $user_login;
        $this->user_statut = $user_statut;
    }

    public function getUser_id(){
        return $this->user_id;
    }
    public function setUser_id(int $user_id){
        $this->user_id = $user_id;
        return $this;
    }

    public function getUser_login(){
        return $this->user_login;
    }
    public function setUser_login(string $user_login){
        $this->user_login = $user_login;
        return $this;
    }

    public function getUser_mdp(){
        return $this->user_mdp;
    }
    public function setUser_mdp(string $user_mdp){
        $this->user_mdp = $user_mdp;
        return $this;
    }

    public function getUser_statut(){
        return $this->user_statut;
    }
    public function setUser_statut(string $user_statut){
        $this->user_statut = $user_statut;
        return $this;
    }

    public function getUser_abonnement(){
        return $this->user_abonnement;
    }
    public function setUser_abonnement(Abonnement $user_abonnement){
        $this->user_abonnement = $user_abonnement;
        return $this;
    }




}


?>