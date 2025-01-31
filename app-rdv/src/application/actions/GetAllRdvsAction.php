<?php

namespace apprdv\application\actions;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Exception\HttpNotFoundException;
use Slim\Routing\RouteContext;
use apprdv\application\renderer\JsonRenderer;
use apprdv\core\services\rdv\ServiceRdvInterface;
use apprdv\core\services\rdv\ServiceRdvInvalidDataException;

class GetAllRdvsAction extends AbstractAction
{
    protected ServiceRdvInterface $serviceRdv;

    public function __construct(ServiceRdvInterface $serviceRdv)
    {
        $this->serviceRdv = $serviceRdv;
    }

    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        try {
            $rdv_dto = $this->serviceRdv->getAllRdvs();
        } catch (ServiceRdvInvalidDataException $e) {
            throw new HttpNotFoundException($request, $e->getMessage() . ' : ' . $args['id']);
        }
        $routeParser = RouteContext::fromRequest($request)->getRouteParser();
        $data = [
            'rdv' => $rdv_dto,
            'links' => [
                'self' => ['href' => $routeParser->urlFor('getAllRdvs')],
            ]
        ];

        return JsonRenderer::render($response, 200, $data);
    }
}