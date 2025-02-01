<?php
declare(strict_types=1);

namespace auth\actions;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class ValidateTokenAction
{
    private $jwtSettings;

    public function __construct(array $jwtSettings)
    {
        $this->jwtSettings = $jwtSettings;
    }

    public function __invoke(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $authHeader = $request->getHeaderLine('Authorization');
        error_log("Auth header: " . $authHeader);
        
        if (empty($authHeader) || !preg_match('/^Bearer\s+(.*)$/', $authHeader, $matches)) {
            error_log("Token manquant ou format invalide");
            $response->getBody()->write(json_encode([
                'status' => 401,
                'message' => 'Token manquant ou format invalide'
            ]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(401);
        }
        
        $token = $matches[1];
        error_log("Token extrait: " . $token);
        
        try {
            // Vérifier le token JWT
            error_log("Secret utilisé: " . $this->jwtSettings['secret']);
            $decoded = JWT::decode($token, new Key($this->jwtSettings['secret'], 'HS256'));
            error_log("Token décodé: " . json_encode($decoded));
            
            $response->getBody()->write(json_encode([
                'status' => 200,
                'message' => 'Token valide',
                'user' => [
                    'id' => $decoded->sub,
                    'email' => $decoded->email,
                    'role' => $decoded->role
                ]
            ]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
            
        } catch (\Exception $e) {
            error_log("Erreur de validation du token: " . $e->getMessage());
            $response->getBody()->write(json_encode([
                'status' => 401,
                'message' => 'Token invalide ou expiré: ' . $e->getMessage()
            ]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(401);
        }
    }
}
