<?php

namespace apppraticien\application\actions;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Routing\RouteContext;
use apppraticien\application\renderer\JsonRenderer;
use apppraticien\core\services\praticien\ServicePraticienInterface;
use apppraticien\core\services\rdv\ServiceRdvInvalidDataException;

class GetAllPraticiensAction extends AbstractAction
{
    protected ServicePraticienInterface $servicePraticien;

    public function __construct(ServicePraticienInterface $servicePraticien)
    {
        $this->servicePraticien = $servicePraticien;
    }

    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        try {
            $praticiens = $this->servicePraticien->getAllPraticiens();
            $routeParser = RouteContext::fromRequest($request)->getRouteParser();
            $newPraticiens = [];
            foreach ($praticiens as $praticien) {
                $newPraticiens[] = [
                    'ID' => $praticien->ID,
                    'nom' => $praticien->nom,
                    'prenom' => $praticien->prenom,
                    'adresse' => $praticien->adresse,
                    'tel' => $praticien->tel,
                    'specialite' => $praticien->specialite_label,
                ];
            }
            $data = [
                'praticiens' => $newPraticiens,
                'links' => [
                    'self' => ['href' => $routeParser->urlFor('getAllPraticiens')],
                ]
            ];
            return JsonRenderer::render($response, 200, $data);
        } catch (ServiceRdvInvalidDataException $e) {
            return JsonRenderer::render($response, 404, ['error' => $e->getMessage()]);
        }
    }
}