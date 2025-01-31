<?php

namespace apprdv\core\services\rdv;

use apprdv\core\domain\entities\rdv\Rdv;
use apprdv\core\dto\practicien\PraticienDTO;
use apprdv\core\dto\rdv\RdvDTO;

interface ServiceRdvInterface
{

    public function consultRdv(string $rdvID): RdvDTO;
    public function createRdv($rdv): RdvDTO;
    public function getAllRdvs(): array;
    public function updateRdv($rdv): RdvDTO;
    public function deleteRdv(string $rdvID): RdvDTO;
    public function getRdvsByPraticienId(string $praticienId): array;
    public function getRdvsByPraticienAndWeek(string $praticienId, string $week): array;


}