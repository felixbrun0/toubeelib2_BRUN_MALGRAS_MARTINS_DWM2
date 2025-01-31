<?php

namespace apprdv\core\domain\entities\rdv;

use DateTimeImmutable;
use PhpParser\Node\Scalar\String_;
use apprdv\core\domain\entities\Entity;
use apprdv\core\domain\entities\patient\Patient;
use apprdv\core\domain\entities\praticien\Praticien;
use apprdv\core\domain\entities\praticien\Specialite;
use apprdv\core\dto\rdv\RdvDTO;

class Rdv extends Entity
{
    protected ?string $ID;
    protected DateTimeImmutable $date;
    protected int $duree;
    protected string $praticienId;
    protected string $patientId;
    protected string $specialite;
    protected string $statut;

    public function __construct(DateTimeImmutable $date, int $duree, string $praticienId, string $patientId, string $specialite)
    {
        $this->date = $date;
        $this->duree = $duree;
        $this->praticienId = $praticienId;
        $this->patientId = $patientId;
        $this->specialite = $specialite;
        $this->statut = "prévu";
    }

    public function deleteRDV (): void
    {
        $this->statut = "annulé";
    }

    public function modifierPatientRDV (Patient $patient)
    {
        $this->patientId = $patient;
    }

    public function modifierSpecialite($specialite)
    {
        $this->specialite = $specialite;
    }

    public function honorerRDV ()
    {
        $this->statut = "honoré";
    }
    public function payerRDV ()
    {
        $this->statut = "payé";
    }
    public function nePasHonorerRDV ()
    {
        $this->statut = "non honoré";
    }

    public function getID(): string
    {
        return $this->ID;
    }

    public function getDate(): DateTimeImmutable
    {
        return $this->date;
    }

    public function getDuree(): int
    {
        return $this->duree;
    }

    public function getPraticienId(): string
    {
        return $this->praticienId;
    }

    public function getPatientId(): string
    {
        return $this->patientId;
    }

    public function getSpecialite(): String
    {
        return $this->specialite;
    }

    public function getStatut(): string
    {
        return $this->statut;
    }

    public function toDTO(): RdvDTO
    {
        return new RdvDTO($this);
    }
}