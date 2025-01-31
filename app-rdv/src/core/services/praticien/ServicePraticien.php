<?php

namespace apprdv\core\services\praticien;

use Ramsey\Uuid\Uuid;
use apprdv\core\domain\entities\praticien\Praticien;
use apprdv\core\dto\practicien\InputPraticienDTO;
use apprdv\core\dto\practicien\PraticienDTO;
use apprdv\core\dto\practicien\SpecialiteDTO;
use apprdv\core\repositoryInterfaces\PraticienRepositoryInterface;
use apprdv\core\repositoryInterfaces\RepositoryEntityNotFoundException;

class ServicePraticien implements ServicePraticienInterface
{
    private PraticienRepositoryInterface $praticienRepository;

    public function __construct(PraticienRepositoryInterface $praticienRepository)
    {
        $this->praticienRepository = $praticienRepository;
    }

    public function createPraticien(InputPraticienDTO $inputPraticienDTO): PraticienDTO
    {
        $id = Uuid::uuid4()->toString();
        $praticien = new Praticien(
            $inputPraticienDTO->nom,
            $inputPraticienDTO->prenom,
            $inputPraticienDTO->adresse,
            $inputPraticienDTO->tel,
            $id,
        );

        $this->praticienRepository->save($praticien);

        return new PraticienDTO($praticien);
    }

    public function getPraticienById(string $id): PraticienDTO
    {
        try {
            $praticien = $this->praticienRepository->getPraticienById($id);
            $praticienDTO = new PraticienDTO($praticien);
            return $praticienDTO;
        } catch(RepositoryEntityNotFoundException $e) {
            throw new ServicePraticienInvalidDataException($e->getMessage());
        }
    }

    public function getSpecialiteById(string $id): SpecialiteDTO
    {
        try {
            $specialite = $this->praticienRepository->getSpecialiteById($id);
            return $specialite->toDTO();
        } catch(RepositoryEntityNotFoundException $e) {
            throw new ServicePraticienInvalidDataException('invalid Specialite ID');
        }
    }

    public function getAllPraticiens(): array
    {
        $praticiens = $this->praticienRepository->getAllPraticiens();
        $praticiensDTO = [];
        foreach($praticiens as $praticien) {
            $praticiensDTO[] = new PraticienDTO($praticien);
        }
        return $praticiensDTO;
    }
}