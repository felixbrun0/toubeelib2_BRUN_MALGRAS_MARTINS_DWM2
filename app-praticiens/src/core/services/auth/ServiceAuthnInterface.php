<?php

namespace apppraticien\core\services\auth;

use Ramsey\Uuid\Uuid;
use apppraticien\core\dto\auth\AuthDTO;
use apppraticien\core\dto\credentials\CredentialsDTO;

interface ServiceAuthnInterface
{
    public function createUser(CredentialsDTO $credentials, int $role): string;
    public function byCredentials(CredentialsDTO $credentials): AuthDTO;
}