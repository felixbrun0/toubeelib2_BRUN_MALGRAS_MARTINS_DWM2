<?php

namespace gateway\application\actions;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Exception\ServerException;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Exception\HttpForbiddenException;
use Slim\Exception\HttpInternalServerErrorException;
use Slim\Exception\HttpNotFoundException;
use Slim\Exception\HttpUnauthorizedException;


class GenericActionRdv extends AbstractAction
{
    private Client $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }
    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $method = $request->getMethod();
        $path = $request->getUri()->getPath();
        $options = ['query' => $request->getQueryParams()];
        if ($method === 'POST' || $method === 'PUT' || $method === 'PATCH') {
            $options['json'] = $request->getParsedBody();
        }
        try {
            $apiResponse = $this->client->request($method, $path, $options);
            $data = json_decode($apiResponse->getBody()->getContents(), true);
            $response->getBody()->write(json_encode($data));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
        } catch (ConnectException | ServerException $e) {
            throw new HttpInternalServerErrorException($request, $e->getMessage());
        } catch (ClientException $e) {
            match ($e->getCode()) {
                401 => throw new HttpUnauthorizedException($request),
                403 => throw new HttpForbiddenException($request),
                404 => throw new HttpNotFoundException($request)
            };
        }
    }
}