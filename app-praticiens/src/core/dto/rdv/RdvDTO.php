<?php

namespace apppraticien\core\dto\rdv;

use DateTimeImmutable;
use apppraticien\core\domain\entities\praticien\Praticien;
use apppraticien\core\domain\entities\rdv\Rdv;
use apppraticien\core\dto\DTO;

class RdvDTO extends DTO implements \JsonSerializable
{
    protected string $ID;
    protected DateTimeImmutable $date;
    protected string $praticienId;
    protected string $patientId;
    protected string $statut;
    protected string $specialite;


    public function __construct(Rdv $rdv)
    {
        $this->ID = $rdv->getID();
        $this->date = $rdv->date;
        $this->praticienId = $rdv->praticienId;
        $this->patientId = $rdv->patientId;
        $this->statut = $rdv->statut;
        $this->specialite = $rdv->specialite;
    }

public function jsonSerialize(): array
{
    return [
        'ID' => $this->ID,
        'date' => $this->date->format('Y-m-d H:i'),
        'praticienId' => $this->praticienId,
        'patientId' => $this->patientId,
        'statut' => $this->statut,
        'specialite' => $this->specialite
    ];

}
public function getId()
{
    return $this->ID;
}
}