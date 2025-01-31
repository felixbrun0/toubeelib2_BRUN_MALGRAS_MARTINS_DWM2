<?php

namespace apppraticien\application\providers\auth;

use apppraticien\core\dto\auth\AuthDTO;
use apppraticien\core\services\auth\ServiceAuthnInterface;
use apppraticien\core\dto\credentials\CredentialsDTO;

class JWTAuthnProvider implements AuthnProviderInterface
{
    private JWTManager $jwtManager;
    private ServiceAuthnInterface $serviceAuthn;

    public function __construct(JWTManager $jwtManager, ServiceAuthnInterface $serviceAuthn)
    {
        $this->jwtManager = $jwtManager;
        $this->serviceAuthn = $serviceAuthn;
    }

    public function register(CredentialsDTO $credentials, int $role): void
    {
        $this->serviceAuthn->createUser($credentials, $role);
    }

    public function login(CredentialsDTO $credentials): AuthDTO
    {
        $authDTO = $this->serviceAuthn->byCredentials($credentials);
        return $authDTO;
    }

    public function refresh(string $token): AuthDTO
    {
        $credential = $this->jwtManager->decodeToken($token);
        $payload = [
            'iss' => 'http://auth.myapp.net/',
            'iat' => time(),
            'exp' => time() + 3600,
            'sub' => $credential->ID,
            'data' => [
                'login' => $credential->login,
                'password' => $credential->password,
                'role' => $credential->role
            ]
        ];
        $rtoken = $this->jwtManager->createRefreshToken($payload);
        return new AuthDTO(
            $credential->ID,
            $credential->login,
            $credential->password,
            null,
            $rtoken
        );
    }

    public function getSignedInUser(string $token): AuthDTO
    {
        $credential = $this->jwtManager->decodeToken($token);
        return new AuthDTO(
            $credential->ID,
            $credential->login,
            $credential->password,
            $token,
            null
        );
    }

}