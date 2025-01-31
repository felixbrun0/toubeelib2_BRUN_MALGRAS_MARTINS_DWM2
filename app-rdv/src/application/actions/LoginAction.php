<?php

namespace apprdv\application\actions;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use apprdv\application\providers\auth\AuthnProviderInterface;
use apprdv\core\services\auth\ServiceAuthn;

class LoginAction extends AbstractAction
{
    protected AuthnProviderInterface $authnProvider;

    public function __construct(AuthnProviderInterface $authnProvider)
    {
        $this->authnProvider = $authnProvider;
    }
    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $data = $request->getParsedBody();
        $authDTO = $this->authnProvider->login($data);
        $response->getBody()->write(json_encode($authDTO->jsonSerialize()));
        return $response->withStatus(200)
            ->withHeader('Content-Type', 'application/json');
    }

}