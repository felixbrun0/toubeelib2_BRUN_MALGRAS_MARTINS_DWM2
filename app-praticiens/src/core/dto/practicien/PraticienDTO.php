<?php

namespace apppraticien\core\dto\practicien;

use apppraticien\core\domain\entities\praticien\Praticien;
use apppraticien\core\dto\DTO;

class PraticienDTO extends DTO
{
    protected ?string $ID;
    protected string $nom;
    protected string $prenom;
    protected string $adresse;
    protected string $tel;
    protected string $specialite_label = ''; // string vide par default

    public function __construct(Praticien $praticien)
    {
        $this->ID = $praticien->getID();
        $this->nom = $praticien->nom;
        $this->prenom = $praticien->prenom;
        $this->adresse = $praticien->adresse;
        $this->tel = $praticien->tel;

        // au cas ou pas de specialite
        if ($praticien->specialite !== null) {
            $this->specialite_label = $praticien->specialite->label;
        }
    }
    public function getId()
    {
        return $this->ID;
    }

    public function jsonSerialize(): array
    {
        return [
            'ID' => $this->ID,
            'nom' => $this->nom,
            'prenom' => $this->prenom,
            'adresse' => $this->adresse,
            'tel' => $this->tel,
            'specialite' => $this->specialite_label,
        ];
    }
}
