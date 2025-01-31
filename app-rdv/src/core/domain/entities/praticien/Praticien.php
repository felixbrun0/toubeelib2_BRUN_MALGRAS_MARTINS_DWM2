<?php

namespace apprdv\core\domain\entities\praticien;

use apprdv\core\domain\entities\Entity;
use apprdv\core\dto\practicien\PraticienDTO;

class Praticien extends Entity
{
    protected ?string $ID ;
    protected string $nom;
    protected string $prenom;
    protected string $adresse;
    protected string $tel;
    protected ?Specialite $specialite = null; // Version simplifiée : pass de spécialité

    public function __construct(string $nom, string $prenom, string $adresse, string $tel, string $ID)
    {
        $this->nom = $nom;
        $this->prenom = $prenom;
        $this->adresse = $adresse;
        $this->tel = $tel;
        $this->ID = $ID;
    }

    public function setSpecialite(Specialite $specialite): void
    {
        $this->specialite = $specialite;
    }

    public function toDTO(): PraticienDTO
    {
        return new PraticienDTO($this);
    }

    public function getNom(): string
    {
        return $this->nom;
    }

    public function getPrenom(): string
    {
        return $this->prenom;
    }

    public function getAdresse(): string
    {
        return $this->adresse;
    }

    public function getTelephone(): string
    {
        return $this->tel;
    }

    public function getSpecialite(): ?Specialite
    {
        return $this->specialite;
    }

}