<?php

namespace apppraticien\application\providers\auth;

use apppraticien\core\dto\auth\AuthDTO;
use apppraticien\core\dto\credentials\CredentialsDTO;

interface AuthnProviderInterface
{
    public function register(CredentialsDTO $credentials, int $role): void;
    public function login(CredentialsDTO $credentials): AuthDTO;
    public function refresh(string $token): AuthDTO;
    public function getSignedInUser(string $token): AuthDTO;
}