<?php

namespace apprdv\core\domain\entities\patient;

use apprdv\core\domain\entities\Entity;
use apprdv\core\dto\patient\PatientDTO;

class Patient extends Entity
{
    protected ?string $ID;
    protected string $nom;
    protected string $prenom;
    protected string $adresse;
    protected string $tel;
    protected string $mail;
    protected string $dossierMedical;
    
    
    public function __construct(string $nom, string $prenom, string $adresse, string $tel, string $mail, string $dossierMedical)
    {
        $this->nom = $nom;
        $this->prenom = $prenom;
        $this->adresse = $adresse;
        $this->tel = $tel;
        $this->mail = $mail;
        $this->dossierMedical = $dossierMedical;
    }


    public function toDTO(): PatientDTO
    {
        return new PatientDTO($this);
    }
}