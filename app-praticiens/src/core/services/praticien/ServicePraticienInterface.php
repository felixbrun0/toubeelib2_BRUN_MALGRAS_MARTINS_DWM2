<?php

namespace apppraticien\core\services\praticien;

use apppraticien\core\dto\practicien\InputPraticienDTO;
use apppraticien\core\dto\practicien\PraticienDTO;
use apppraticien\core\dto\practicien\SpecialiteDTO;

interface ServicePraticienInterface
{

    public function createPraticien(InputPraticienDTO $p): PraticienDTO;
    public function getPraticienById(string $id): PraticienDTO;
    public function getSpecialiteById(string $id): SpecialiteDTO;
    public function getAllPraticiens(): array;

}