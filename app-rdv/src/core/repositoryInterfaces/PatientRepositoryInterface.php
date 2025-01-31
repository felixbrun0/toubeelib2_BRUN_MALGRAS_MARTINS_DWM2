<?php

namespace apprdv\core\repositoryInterfaces;

use apprdv\core\domain\entities\patient\Patient;

interface PatientRepositoryInterface
{

    public function save(Patient $patient): string;
    public function getPatientById(string $id): Patient;

}