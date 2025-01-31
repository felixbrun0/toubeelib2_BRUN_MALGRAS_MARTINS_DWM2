<?php

namespace apprdv\core\dto\patient;

use apprdv\core\domain\entities\patient\Patient;
use apprdv\core\dto\DTO;

class PatientDTO extends DTO
{
    protected string $ID;
    protected string $nom;
    protected string $prenom;
    protected string $adresse;
    protected string $tel;
    protected string $mail;
    protected string $dossierMedical;


    public function __construct(Patient $p)
    {
        $this->ID = $p->getID();
        $this->nom = $p->nom;
        $this->prenom = $p->prenom;
        $this->adresse = $p->adresse;
        $this->tel = $p->tel;
        $this->mail = $p->mail;
        $this->dossierMedical = $p->dossierMedical;
    }

    public function getID(): string
    {
        return $this->ID;
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

    public function getTel(): string
    {
        return $this->tel;
    }

    public function getMail(): string
    {
        return $this->mail;
    }

    public function getDossierMedical(): string
    {
        return $this->dossierMedical;
    }


}