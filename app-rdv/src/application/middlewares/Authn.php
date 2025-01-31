<?php

namespace apprdv\application\middlewares;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use apprdv\application\providers\auth\AuthnProviderInterface;

class Authn
{
    private AuthnProviderInterface $authnProvider;

    public function __construct(AuthnProviderInterface $authnProvider)
    {
        $this->authnProvider = $authnProvider;
    }

    public function __invoke(ServerRequestInterface $request, callable $next): ResponseInterface
    {
        $token = $request->getHeaderLine('Authorization');
        $authDTO = $this->authnProvider->getSignedInUser($token);
        $request = $request->withAttribute('authDTO', $authDTO);
        return $next($request);
    }
}