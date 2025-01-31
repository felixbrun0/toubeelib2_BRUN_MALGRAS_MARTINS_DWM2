<?php

namespace apppraticien\infrastructure\db;

use Ramsey\Uuid\Uuid;
use apppraticien\core\domain\entities\praticien\Praticien;
use apppraticien\core\domain\entities\praticien\Specialite;
use apppraticien\core\repositoryInterfaces\PraticienRepositoryInterface;
use apppraticien\core\repositoryInterfaces\RepositoryEntityNotFoundException;

class PDOPraticienRepository implements PraticienRepositoryInterface
{
    private $pdo;

    public function __construct(\PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    /**
     * @throws RepositoryEntityNotFoundException
     */
    public function getSpecialiteById(string $id): Specialite
    {
        try {
            $query = $this->pdo->prepare('SELECT * FROM specialite WHERE id = :id');
            $query->execute(['id' => $id]);
            $specialite = $query->fetch();
            if ($specialite === false) {
                throw new RepositoryEntityNotFoundException('Specialite not found');
            }
            return new Specialite(
                $specialite['id'],
                $specialite['label'],
                $specialite['description']
            );
        }catch (\PDOException $e) {
            throw new RepositoryEntityNotFoundException('Error while fetching specialite');
        }
    }

    /**
     * @throws RepositoryEntityNotFoundException
     */
    public function save(Praticien $praticien): string
    {
        try {
            if ($praticien->getId() !== null) {
                $stmt = $this->pdo->prepare("UPDATE praticien SET nom = :nom, prenom = :prenom, adresse = :adresse, telephone = :telephone, specialite_id = :specialite_id WHERE id = :id");
            } else {
                $id = Uuid::uuid4()->toString();
                $praticien->setId($id);
                $stmt = $this->pdo->prepare("INSERT INTO praticien (id, nom, prenom, adresse, telephone, specialite_id) VALUES (:id, :nom, :prenom, :adresse, :telephone, :specialite_id)");
            }
            Uuid::uuid4()->toString();
            $stmt->execute([
                'id' => $praticien->getId(),
                'nom' => $praticien->getNom(),
                'prenom' => $praticien->getPrenom(),
                'adresse' => $praticien->getAdresse(),
                'telephone' => $praticien->getTelephone(),
                'specialite_id' => $praticien->getSpecialite()
            ]);
        return $praticien->getId();
        }catch (\PDOException $e) {
            throw new RepositoryEntityNotFoundException('Error while saving praticien');
        }
    }

    public function getAllPraticiens(): array
    {
        try {
            $query = $this->pdo->prepare('SELECT * FROM praticien');
            $query->execute();
            $praticiens = $query->fetchAll();
            $result = [];
            foreach ($praticiens as $praticien) {
                $p = new Praticien($praticien['nom'], $praticien['prenom'], $praticien['adresse'], $praticien['telephone'], $praticien['id']);
                $result[] = $p;
            }
            return $result;
        } catch (\PDOException $e) {
            throw new RepositoryEntityNotFoundException('Error while fetching praticiens');
        }
    }


    /**
     * @throws RepositoryEntityNotFoundException
     */
    public function getPraticienById(string $id): Praticien
    {
        try {
            $query = $this->pdo->prepare('SELECT * FROM praticien WHERE id = :id');
            $query->execute(['id' => $id]);
            $praticien = $query->fetch();
            if ($praticien === false) {
                throw new RepositoryEntityNotFoundException('Praticien not found');
            }
            $p = new Praticien($praticien['nom'], $praticien['prenom'], $praticien['adresse'], $praticien['telephone'], $praticien['id']);
//            $p->setId($praticien['id']);
//            $p->setSpecialite($this->getSpecialiteById($praticien['specialite_id']));
            return $p;
        }catch (\PDOException $e) {
            throw new RepositoryEntityNotFoundException('Error while fetching praticien');
        }
    }
}