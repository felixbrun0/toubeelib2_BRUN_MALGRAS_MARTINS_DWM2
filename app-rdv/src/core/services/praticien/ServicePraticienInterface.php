<?php

namespace apprdv\core\services\praticien;

use apprdv\core\dto\practicien\InputPraticienDTO;
use apprdv\core\dto\practicien\PraticienDTO;
use apprdv\core\dto\practicien\SpecialiteDTO;

interface ServicePraticienInterface
{

    public function createPraticien(InputPraticienDTO $p): PraticienDTO;
    public function getPraticienById(string $id): PraticienDTO;
    public function getSpecialiteById(string $id): SpecialiteDTO;
    public function getAllPraticiens(): array;

}