<?php

namespace apprdv\core\services\auth;

use Ramsey\Uuid\Uuid;
use apprdv\core\dto\auth\AuthDTO;
use apprdv\core\dto\credentials\CredentialsDTO;

interface ServiceAuthnInterface
{
    public function createUser(CredentialsDTO $credentials, int $role): string;
    public function byCredentials(CredentialsDTO $credentials): AuthDTO;
}