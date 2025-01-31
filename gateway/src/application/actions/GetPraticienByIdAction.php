<?php
namespace gateway\application\actions;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

class GetPraticienByIdAction extends AbstractAction
{
    private Client $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        try {
            $apiResponse = $this->client->request('GET', '/praticiens/' . $args['id']);
            $data = json_decode($apiResponse->getBody()->getContents(), true);
            $response->getBody()->write(json_encode($data));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
        } catch (RequestException $e) {
            $response->getBody()->write(json_encode(['error' => 'API request failed']));
            return $response->withStatus(404)->withHeader('Content-Type', 'application/json');
        }
    }
}