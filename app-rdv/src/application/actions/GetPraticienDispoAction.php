<?php
namespace apprdv\application\actions;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Exception\HttpNotFoundException;
use apprdv\core\services\rdv\ServiceRdvInterface;
use apprdv\core\services\rdv\ServiceRdvInvalidDataException;

class GetPraticienDispoAction extends AbstractAction
{
    protected ServiceRdvInterface $serviceRdv;

    public function __construct(ServiceRdvInterface $serviceRdv)
    {
        $this->serviceRdv = $serviceRdv;
    }

    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $praticienId = (string)$args['praticienId'];

        try {
            $rdvs = $this->serviceRdv->getRdvsByPraticienId($praticienId);
        } catch (ServiceRdvInvalidDataException $e) {
            throw new HttpNotFoundException($request, $e->getMessage() . ' : ' . $praticienId);
        }

        $response->getBody()->write(json_encode($rdvs));

        return $response->withStatus(200)
            ->withHeader('Content-Type', 'application/json');
    }
}