<?php

namespace apppraticien\core\repositoryInterfaces;

use apppraticien\core\domain\entities\praticien\Praticien;
use apppraticien\core\domain\entities\praticien\Specialite;

interface PraticienRepositoryInterface
{

    public function getSpecialiteById(string $id): Specialite;
    public function save(Praticien $praticien): string;
    public function getPraticienById(string $id): Praticien;
    public function getAllPraticiens(): array;

}