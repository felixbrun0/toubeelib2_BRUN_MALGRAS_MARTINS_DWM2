<?php

namespace apprdv\application\providers\auth;

use apprdv\core\dto\auth\AuthDTO;
use apprdv\core\dto\credentials\CredentialsDTO;

interface AuthnProviderInterface
{
    public function register(CredentialsDTO $credentials, int $role): void;
    public function login(CredentialsDTO $credentials): AuthDTO;
    public function refresh(string $token): AuthDTO;
    public function getSignedInUser(string $token): AuthDTO;
}