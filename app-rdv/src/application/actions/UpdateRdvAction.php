<?php

namespace apprdv\application\actions;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use apprdv\application\renderer\JsonRenderer;
use apprdv\core\services\rdv\ServiceRdvInterface;

class UpdateRdvAction extends AbstractAction
{
    protected ServiceRdvInterface $serviceRdv;
    public function __construct(ServiceRdvInterface $serviceRdv)
    {
        $this->serviceRdv = $serviceRdv;
    }

    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $data = $request->getParsedBody();
        $data['ID'] = $args['id'];
        $OldRdv = json_encode($this->serviceRdv->consultRdv($data['ID'])->jsonSerialize());
        $rdv = $this->serviceRdv->updateRdv($data);

        $response->getBody()->write($OldRdv.json_encode($rdv->jsonSerialize()));

        $data = [
            'links' => [
                'self' => ['href' => '/rdv/'.$rdv->getID()],
                'update' => ['href' => '/rdv/'.$rdv->getID()]
            ]
        ];

        return JsonRenderer::render($response, 200, $data);
    }
}
