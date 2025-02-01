<?php

namespace gateway\application\middlewares;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use GuzzleHttp\Client;
use Slim\Exception\HttpUnauthorizedException;

class AuthMiddleware implements MiddlewareInterface
{
    private Client $client;
    private string $authServiceUrl;

    public function __construct(Client $client, string $authServiceUrl)
    {
        $this->client = $client;
        $this->authServiceUrl = $authServiceUrl;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $token = $this->extractToken($request);
        
        if (empty($token)) {
            throw new HttpUnauthorizedException($request, "Token manquant");
        }

        try {
            $response = $this->client->request('POST', $this->authServiceUrl . '/tokens/validate', [
                'headers' => [
                    'Authorization' => 'Bearer ' . $token
                ]
            ]);

            if ($response->getStatusCode() !== 200) {
                throw new HttpUnauthorizedException($request, "Token invalide");
            }

            // Token valide, on continue le traitement
            return $handler->handle($request);

        } catch (\Exception $e) {
            throw new HttpUnauthorizedException($request, "Erreur d'authentification: " . $e->getMessage());
        }
    }

    private function extractToken(ServerRequestInterface $request): ?string
    {
        $header = $request->getHeaderLine('Authorization');
        
        if (empty($header) || !preg_match('/Bearer\s+(.*)$/i', $header, $matches)) {
            return null;
        }

        return $matches[1];
    }
}
