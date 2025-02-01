<?php

namespace apprdv\application\middlewares;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Slim\Exception\HttpForbiddenException;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class AuthorizationMiddleware implements MiddlewareInterface
{
    private string $jwtSecret;

    public function __construct(string $jwtSecret)
    {
        $this->jwtSecret = $jwtSecret;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $token = $this->extractToken($request);
        $payload = $this->decodeToken($token);

        // Vérifier les autorisations en fonction de la route et du rôle de l'utilisateur
        $route = $request->getUri()->getPath();
        $method = $request->getMethod();
        
        if (!$this->isAuthorized($route, $method, $payload)) {
            throw new HttpForbiddenException($request, "Accès non autorisé");
        }

        // Ajouter les informations de l'utilisateur à la requête pour une utilisation ultérieure
        $request = $request->withAttribute('user', $payload);
        
        return $handler->handle($request);
    }

    private function extractToken(ServerRequestInterface $request): string
    {
        $header = $request->getHeaderLine('Authorization');
        
        if (empty($header) || !preg_match('/Bearer\s+(.*)$/i', $header, $matches)) {
            throw new HttpForbiddenException($request, "Token manquant ou invalide");
        }

        return $matches[1];
    }

    private function decodeToken(string $token): object
    {
        try {
            return JWT::decode($token, new Key($this->jwtSecret, 'HS256'));
        } catch (\Exception $e) {
            throw new HttpForbiddenException($request, "Token invalide");
        }
    }

    private function isAuthorized(string $route, string $method, object $payload): bool
    {
        // Vérifier les autorisations en fonction de la route
        if (preg_match('/^\/rdv\/(\d+)$/', $route, $matches)) {
            // Accès au détail d'un RDV
            $rdvId = $matches[1];
            return $this->canAccessRdv($rdvId, $payload);
        }
        
        if (preg_match('/^\/agenda\/(\d+)$/', $route, $matches)) {
            // Accès à l'agenda d'un praticien
            $praticienId = $matches[1];
            return $this->canAccessAgenda($praticienId, $payload);
        }
        
        if ($route === '/rdv' && $method === 'POST') {
            // Création d'un RDV
            return $this->canCreateRdv($payload);
        }

        // Par défaut, refuser l'accès
        return false;
    }

    private function canAccessRdv(string $rdvId, object $payload): bool
    {
        // Un praticien peut accéder à ses RDV
        if ($payload->role === 'praticien') {
            return true; // Vous devrez vérifier si le RDV appartient au praticien
        }

        // Un patient peut accéder à ses propres RDV
        if ($payload->role === 'patient') {
            return true; // Vous devrez vérifier si le RDV appartient au patient
        }

        return false;
    }

    private function canAccessAgenda(string $praticienId, object $payload): bool
    {
        // Seul le praticien concerné peut accéder à son agenda
        if ($payload->role === 'praticien' && $payload->id === $praticienId) {
            return true;
        }

        return false;
    }

    private function canCreateRdv(object $payload): bool
    {
        // Les patients et les praticiens peuvent créer des RDV
        return in_array($payload->role, ['patient', 'praticien']);
    }
}
