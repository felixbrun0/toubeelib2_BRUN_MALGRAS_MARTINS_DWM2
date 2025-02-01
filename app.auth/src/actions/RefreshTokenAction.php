<?php
declare(strict_types=1);

namespace auth\actions;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use PDO;

class RefreshTokenAction
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
        $authHeader = $request->getHeaderLine('Authorization');
        
        if (empty($authHeader) || !preg_match('/^Bearer\s+(.*)$/', $authHeader, $matches)) {
            $response->getBody()->write(json_encode([
                'status' => 401,
                'message' => 'Token manquant ou format invalide'
            ]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(401);
        }
        
        $token = $matches[1];
        
        try {
            // Vérifier le token JWT actuel
            $decoded = JWT::decode($token, new Key($this->jwtSettings['secret'], 'HS256'));
            
            // Récupérer les informations de l'utilisateur
            $stmt = $this->pdo->prepare('SELECT id, login, role FROM users WHERE id = ?');
            $stmt->execute([$decoded->sub]);
            $user = $stmt->fetch();
            
            if (!$user) {
                throw new \Exception('Utilisateur non trouvé');
            }
            
            // Générer un nouveau token
            $issuedAt = time();
            $expirationTime = $issuedAt + $this->jwtSettings['expires'];
            
            $payload = [
                'iat' => $issuedAt,
                'exp' => $expirationTime,
                'sub' => $user['id'],
                'login' => $user['login'],
                'role' => $user['role']
            ];
            
            $newToken = JWT::encode($payload, $this->jwtSettings['secret'], 'HS256');
            
            $response->getBody()->write(json_encode([
                'status' => 200,
                'message' => 'Token rafraîchi avec succès',
                'token' => $newToken,
                'user' => [
                    'id' => $user['id'],
                    'login' => $user['login'],
                    'role' => $user['role']
                ]
            ]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
            
        } catch (\Exception $e) {
            $response->getBody()->write(json_encode([
                'status' => 401,
                'message' => 'Token invalide ou expiré'
            ]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(401);
        }
    }
}
