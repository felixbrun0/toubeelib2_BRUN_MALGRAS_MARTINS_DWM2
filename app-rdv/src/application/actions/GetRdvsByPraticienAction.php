<?php

namespace apprdv\application\actions;


use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use apprdv\core\services\rdv\ServiceRdvInterface;

class GetRdvsByPraticienAction extends AbstractAction
{
    protected ServiceRdvInterface $serviceRdv;

    public function __construct($serviceRdv)
    {
        $this->serviceRdv = $serviceRdv;
    }

    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $rdvs = $this->serviceRdv->getRdvsByPraticienAndWeek($args['id'], $args['week']);
        $response->getBody()->write(json_encode($rdvs));
        return $response->withHeader('Content-Type', 'application/json');
    }
}