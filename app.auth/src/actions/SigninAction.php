<?php
declare(strict_types=1);

namespace auth\actions;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use PDO;
use Firebase\JWT\JWT;

class SigninAction
{
    private $pdo;
    private $jwtSettings;

    public function __construct(PDO $pdo, array $jwtSettings)
    {
        $this->pdo = $pdo;
        $this->jwtSettings = $jwtSettings;
    }

    public function __invoke(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $data = $request->getParsedBody();

        // Validation des données
        if (!isset($data['email']) || !isset($data['password'])) {
            $response->getBody()->write(json_encode([
                'error' => 'Missing required fields'
            ]));
            return $response
                ->withHeader('Content-Type', 'application/json')
                ->withStatus(400);
        }

        // Recherche de l'utilisateur
        $stmt = $this->pdo->prepare('SELECT id, email, password, role FROM users WHERE email = ?');
        $stmt->execute([$data['email']]);
        $user = $stmt->fetch();

        if (!$user || !password_verify($data['password'], $user['password'])) {
            $response->getBody()->write(json_encode([
                'error' => 'Invalid credentials'
            ]));
            return $response
                ->withHeader('Content-Type', 'application/json')
                ->withStatus(401);
        }

        // Génération du token JWT
        $issuedAt = time();
        $expiresAt = $issuedAt + $this->jwtSettings['expires'];
        
        $payload = [
            'iat' => $issuedAt,
            'exp' => $expiresAt,
            'sub' => $user['id'],
            'email' => $user['email'],
            'role' => $user['role']
        ];

        $token = JWT::encode($payload, $this->jwtSettings['secret'], 'HS256');

        // Génération du refresh token
        $refreshToken = bin2hex(random_bytes(32));
        $refreshExpiresAt = new \DateTime();
        $refreshExpiresAt->modify('+' . $this->jwtSettings['refresh_expires'] . ' seconds');

        // Stockage du refresh token
        $stmt = $this->pdo->prepare('INSERT INTO refresh_tokens (user_id, token, expires_at) VALUES (?, ?, ?)');
        $stmt->execute([$user['id'], $refreshToken, $refreshExpiresAt->format('Y-m-d H:i:s')]);

        $response->getBody()->write(json_encode([
            'token' => $token,
            'refresh_token' => $refreshToken,
            'expires_in' => $this->jwtSettings['expires']
        ]));

        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withStatus(200);
    }
}
