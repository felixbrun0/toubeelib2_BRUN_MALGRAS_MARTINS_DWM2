<?php
declare(strict_types=1);

namespace auth\actions;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use PDO;

class RegisterAction
{
    private $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function __invoke(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $data = $request->getParsedBody();
        
        // Validation des données
        if (!isset($data['email']) || !isset($data['password']) || !isset($data['role'])) {
            $response->getBody()->write(json_encode([
                'error' => 'Missing required fields'
            ]));
            return $response
                ->withHeader('Content-Type', 'application/json')
                ->withStatus(400);
        }

        // Vérifier si l'utilisateur existe déjà
        $stmt = $this->pdo->prepare('SELECT id FROM users WHERE email = ?');
        $stmt->execute([$data['email']]);
        if ($stmt->fetch()) {
            $response->getBody()->write(json_encode([
                'error' => 'User already exists'
            ]));
            return $response
                ->withHeader('Content-Type', 'application/json')
                ->withStatus(409);
        }

        // Hachage du mot de passe
        $hashedPassword = password_hash($data['password'], PASSWORD_DEFAULT);

        // Insertion de l'utilisateur
        $stmt = $this->pdo->prepare('INSERT INTO users (email, password, role) VALUES (?, ?, ?)');
        try {
            $stmt->execute([$data['email'], $hashedPassword, $data['role']]);
            
            $response->getBody()->write(json_encode([
                'message' => 'User registered successfully'
            ]));
            return $response
                ->withHeader('Content-Type', 'application/json')
                ->withStatus(201);
        } catch (\PDOException $e) {
            $response->getBody()->write(json_encode([
                'error' => 'Database error'
            ]));
            return $response
                ->withHeader('Content-Type', 'application/json')
                ->withStatus(500);
        }
    }
}
