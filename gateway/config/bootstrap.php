<?php

use DI\ContainerBuilder;
use Slim\Factory\AppFactory;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

$builder = new ContainerBuilder();
$builder->addDefinitions(__DIR__ . '/settings.php');
$builder->addDefinitions(__DIR__ . '/dependencies.php');

$container = $builder->build();
AppFactory::setContainer($container);
$app = AppFactory::create();

$app->add(function (Request $request, RequestHandlerInterface $handler): Response {
    $response = $handler->handle($request);
    return $response
        ->withHeader('Access-Control-Allow-Origin', '*')
        ->withHeader('Access-Control-Allow-Headers', 'X-Requested-With, Content-Type, Accept, Origin, Authorization')
        ->withHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, PATCH, OPTIONS');
});

$app->addBodyParsingMiddleware();
$app->addRoutingMiddleware();
$app->addErrorMiddleware($container->get('displayErrorDetails'), false, false);

$app = (require_once __DIR__ . '/routes.php')($app);

return $app;