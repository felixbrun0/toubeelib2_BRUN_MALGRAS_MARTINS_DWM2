<?php

namespace apppraticien\core\repositoryInterfaces;

use apppraticien\core\domain\entities\rdv\Rdv;

interface RdvRepositoryInterfaces
{
    public function consultRdv(string $id): Rdv;
    public function save(Rdv $rdv): string;
    public function deleteRdv(string $id): Rdv;
    public function getRdvsByPraticienId(string $praticienId): array;
    public function getRdvsByPraticienAndWeek(string $praticienId, string $week): array;

}