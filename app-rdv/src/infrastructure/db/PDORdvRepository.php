<?php

namespace apprdv\infrastructure\db;

use apprdv\core\domain\entities\rdv\Rdv;
use apprdv\core\repositoryInterfaces\RdvRepositoryInterfaces;
use apprdv\core\repositoryInterfaces\RepositoryEntityNotFoundException;

class PDORdvRepository implements RdvRepositoryInterfaces
{
    private $pdo;

    public function __construct(\PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    /**
     * @throws RepositoryEntityNotFoundException
     */
    public function consultRdv(string $id): Rdv
    {
        try {
            $query = $this->pdo->prepare('SELECT * FROM rdv WHERE id = :id');
            $query->execute(['id' => $id]);
            $rdv = $query->fetch();
            if ($rdv === false) {
                throw new RepositoryEntityNotFoundException('Rdv not found');
            }
            $rdv = new Rdv(
                $rdv['date'],
                $rdv['duree'],
                $rdv['praticien_id'],
                $rdv['patient_id'],
                $rdv['specialite'],
            );
            $rdv->setId($rdv['id']);
            return $rdv;
        } catch (\PDOException $e) {
            throw new RepositoryEntityNotFoundException('Error while fetching rdv');
        }
    }

    /**
     * @throws RepositoryEntityNotFoundException
     */
    public function save(Rdv $rdv): string
    {
        try {
            if ($rdv->getId() !== null) {
                $stmt = $this->pdo->prepare("UPDATE rdv SET date = :date, duree = :duree, praticien_id = :praticien_id, patient_id = :patient_id, specialite = :specialite WHERE id = :id");
            } else {
                $stmt = $this->pdo->prepare("INSERT INTO rdv (id, date, duree, praticien_id, patient_id, specialite, statut) VALUES (:id, :date, :duree, :praticien_id, :patient_id, :specialite)");
            }
            $stmt->execute([
                'id' => $rdv->getId(),
                'date' => $rdv->getDate(),
                'duree' => $rdv->getDuree(),
                'praticien_id' => $rdv->getPraticienId(),
                'patient_id' => $rdv->getPatientId(),
                'specialite' => $rdv->getSpecialite(),
            ]);
            return $rdv->getId();
        } catch (\PDOException $e) {
            throw new RepositoryEntityNotFoundException('Error while saving rdv');
        }
    }

    /**
     * @throws RepositoryEntityNotFoundException
     */
    public function deleteRdv(string $id): Rdv
    {
        try {
            $rdv = $this->consultRdv($id);
            $stmt = $this->pdo->prepare("DELETE FROM rdv WHERE id = :id");
            $stmt->execute(['id' => $id]);
            return $rdv;
        } catch (RepositoryEntityNotFoundException $e) {
            throw new RepositoryEntityNotFoundException('Error while deleting rdv');
        }
    }

    /**
     * @throws RepositoryEntityNotFoundException
     */
    public function getRdvsByPraticienId(string $praticienId): array
    {
        try {
            $query = $this->pdo->prepare('SELECT * FROM rdv WHERE praticien_id = :praticien_id');
            $query->execute(['praticien_id' => $praticienId]);
            $rdvs = $query->fetchAll();
            $rdvsArray = [];
            foreach ($rdvs as $rdv) {
                $rdvsArray[] = new Rdv(
                    $rdv['date'],
                    $rdv['duree'],
                    $rdv['praticien_id'],
                    $rdv['patient_id'],
                    $rdv['specialite'],
                );
                $rdv->setId($rdv['id']);
            }
            return $rdvsArray;
        } catch (\PDOException $e) {
            throw new RepositoryEntityNotFoundException('Error while fetching rdvs');
        }
    }

    /**
     * @throws RepositoryEntityNotFoundException
     */
    public function getRdvsByPraticienAndWeek(string $praticienId, string $week): array
    {
        try {
            $query = $this->pdo->prepare('SELECT * FROM rdv WHERE id_praticien = :praticien_id');
            $query->execute(['praticien_id' => $praticienId]);
            $rdvs = $query->fetchAll();
            if (empty($rdvs)) {
                return [];
            }
            $rdvsArray = [];
            foreach ($rdvs as $r) {
                $rdv = new Rdv(
                    new \DateTimeImmutable($r['date']),
                    $r['duree'],
                    $r['id_praticien'],
                    $r['id_patient'],
                    $r['specialite_praticien']
                );
                $rdv->setId($r['id']);
                if ($rdv->getDate()->format('W') === $week) {
                    $rdvsArray[] = $rdv;
                }
            }
            return $rdvsArray;
        } catch (\PDOException $e) {
            throw new RepositoryEntityNotFoundException('Error while fetching rdvs: ' . $e->getMessage());
        }
    }

    /**
     * @throws RepositoryEntityNotFoundException
     */
    public function getAllRdvs(): array
    {
        try {
            $query = $this->pdo->prepare('SELECT * FROM rdv');
            $query->execute();
            $rdvs = $query->fetchAll();
            $rdvsArray = [];
            foreach ($rdvs as $rdv) {
                //$rdvDate = \DateTimeImmutable::createFromFormat('Y-m-d H:i:s', $rdv['date'], new \DateTimeZone('Europe/Paris'));
                $rdvDate = new \DateTimeImmutable($rdv['date']);
                $rdvEntity = new Rdv(
                    $rdvDate,
                    $rdv['duree'],
                    $rdv['id_praticien'],
                    $rdv['id_patient'],
                    $rdv['specialite_praticien'],
                );
                $rdvEntity->setId($rdv['id']);
                $rdvsArray[] = $rdvEntity;
            }
            return $rdvsArray;
        } catch (\PDOException $e) {
            throw new RepositoryEntityNotFoundException('Error while fetching rdvs' . $e->getMessage());
        }
    }

    public function getRdvsByPatientId(string $patientId): array
    {
        try {
            $query = $this->pdo->prepare('SELECT * FROM rdv WHERE patient_id = :patient_id');
            $query->execute(['patient_id' => $patientId]);
            $rdvs = $query->fetchAll();
            $rdvsArray = [];
            foreach ($rdvs as $rdv) {
                $rdvsArray[] = new Rdv(
                    $rdv['date'],
                    $rdv['duree'],
                    $rdv['praticien_id'],
                    $rdv['patient_id'],
                    $rdv['specialite'],
                );
                $rdv->setId($rdv['id']);
            }
            return $rdvsArray;
        } catch (\PDOException $e) {
            throw new RepositoryEntityNotFoundException('Error while fetching rdvs');
        }
    }
}