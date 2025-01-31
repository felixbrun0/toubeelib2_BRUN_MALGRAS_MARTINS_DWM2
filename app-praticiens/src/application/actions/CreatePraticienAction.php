<?php
namespace apppraticien\application\actions;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use apppraticien\core\services\praticien\ServicePraticienInterface;
use apppraticien\core\dto\practicien\InputPraticienDTO;

class CreatePraticienAction extends AbstractAction
{
    protected ServicePraticienInterface $servicePraticien;

    public function __construct(ServicePraticienInterface $servicePraticien)
    {
        $this->servicePraticien = $servicePraticien;
    }

    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $data = $request->getParsedBody();

        if (!isset($data['nom']) || !isset($data['prenom']) || !isset($data['adresse']) || !isset($data['tel']) || !isset($data['specialite'])) {
            $response->getBody()->write(json_encode(['error' => 'Missing required fields']));
            return $response->withStatus(400)->withHeader('Content-Type', 'application/json');
        }

        $inputPraticienDTO = new InputPraticienDTO(
            $data['nom'],
            $data['prenom'],
            $data['adresse'],
            $data['tel'],
            $data['specialite']
        );

        $praticien = $this->servicePraticien->createPraticien($inputPraticienDTO);

        $response->getBody()->write(json_encode($praticien->jsonSerialize()));

        return $response->withStatus(201)
            ->withHeader('Content-Type', 'application/json')
            ->withHeader('Location', '/praticiens/' . $praticien->getId());
    }
}