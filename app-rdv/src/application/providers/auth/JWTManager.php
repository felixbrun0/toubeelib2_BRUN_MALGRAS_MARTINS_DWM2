<?php

namespace apprdv\application\providers\auth;

use Firebase\JWT\JWT;

class JWTManager
{
    public function createAccessToken(array $payload): string
    {
        return 'access_token';
    }

    public function createRefreshToken(array $payload): string
    {
        $token = JWT::encode($payload, 'refresh_token', 'HS256');
        return $token;
    }

    public function decodeToken(string $token): array
    {
        return JWT::decode($token, 'refresh_token');
    }
}