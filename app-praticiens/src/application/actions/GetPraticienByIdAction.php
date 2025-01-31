<?php

namespace apppraticien\application\actions;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Routing\RouteContext;
use apppraticien\application\renderer\JsonRenderer;
use apppraticien\core\services\praticien\ServicePraticienInterface;
use apppraticien\core\services\rdv\ServiceRdvInvalidDataException;

class GetPraticienByIdAction extends AbstractAction
{
    protected ServicePraticienInterface $servicePraticien;

    public function __construct(ServicePraticienInterface $servicePraticien)
    {
        $this->servicePraticien = $servicePraticien;
    }

    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        try {
            $praticien = $this->servicePraticien->getPraticienById((string)$args['id']);
            $routeParser = RouteContext::fromRequest($request)->getRouteParser();
            $data = [
                'ID' => $praticien->ID,
                'nom' => $praticien->nom,
                'prenom' => $praticien->prenom,
                'adresse' => $praticien->adresse,
                'tel' => $praticien->tel,
                'specialite' => $praticien->specialite_label,
                'links' => [
                    'self' => ['href' => $routeParser->urlFor('getPraticienDispo', ['praticienId' => $praticien->ID])],
                    'rdvs' => ['href' => $routeParser->urlFor('getRdvsByPraticien', ['id' => $praticien->ID, 'week' => 0])],
                ]
            ];
            return JsonRenderer::render($response, 200, $data);
        } catch (ServiceRdvInvalidDataException $e) {
            return JsonRenderer::render($response, 404, ['error' => $e->getMessage()]);
        }
    }
}