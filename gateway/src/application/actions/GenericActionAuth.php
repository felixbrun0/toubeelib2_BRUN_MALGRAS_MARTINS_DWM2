<?php
declare(strict_types=1);

namespace gateway\application\actions;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use GuzzleHttp\Client;
use Slim\Exception\HttpInternalServerErrorException;

class GenericActionAuth
{
    private $client;
    private $baseUrl;
    
    public function __construct(Client $client, string $baseUrl)
    {
        $this->client = $client;
        $this->baseUrl = $baseUrl;
    }

    public function __invoke(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        try {
            // Récupérer le corps de la requête et les en-têtes
            $body = $request->getParsedBody();
            $headers = [
                'Content-Type' => 'application/json',
                'Accept' => 'application/json'
            ];
            
            // Ajouter l'en-tête Authorization s'il est présent
            $authHeader = $request->getHeaderLine('Authorization');
            if (!empty($authHeader)) {
                $headers['Authorization'] = $authHeader;
            }
            
            // Utiliser directement le chemin de la requête
            $path = $request->getUri()->getPath();
            
            // Transférer la requête au service d'authentification
            $authResponse = $this->client->request($request->getMethod(), $this->baseUrl . $path, [
                'json' => $body,
                'headers' => $headers,
                'http_errors' => false
            ]);
            
            // Copier le statut et le corps de la réponse
            $response->getBody()->write((string) $authResponse->getBody());
            return $response
                ->withHeader('Content-Type', 'application/json')
                ->withStatus($authResponse->getStatusCode());
                
        } catch (\Exception $e) {
            throw new HttpInternalServerErrorException($request, $e->getMessage());
        }
    }
}
