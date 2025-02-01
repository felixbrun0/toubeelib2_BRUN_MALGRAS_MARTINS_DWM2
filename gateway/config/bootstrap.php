<?php

use DI\ContainerBuilder;
use Slim\Factory\AppFactory;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

$builder = new ContainerBuilder();
$settings = require_once __DIR__ . '/settings.php';
$dependencies = require_once __DIR__ . '/dependencies.php';
$builder->addDefinitions($settings);
$builder->addDefinitions($dependencies);

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
$displayErrorDetails = $container->get('displayErrorDetails');

// Configuration du middleware d'erreur pour retourner les erreurs en JSON
$errorMiddleware = $app->addErrorMiddleware($displayErrorDetails, false, false);
$errorHandler = $errorMiddleware->getDefaultErrorHandler();
$errorHandler->forceContentType('application/json');

$app = (require_once __DIR__ . '/routes.php')($app);

return $app;