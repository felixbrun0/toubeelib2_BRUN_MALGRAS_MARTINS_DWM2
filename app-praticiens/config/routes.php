<?php
declare(strict_types=1);

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;


return function( \Slim\App $app):\Slim\App {

//    $app->add(\apppraticien\application\middlewares\Cors::class);

    $app->options('/{routes:.+}', function (Request $rq, Response $rs, array $args): Response {
        return $rs;
    });

    $app->get('/',
        \apppraticien\application\actions\HomeAction::class);

    $app->get('/praticiens/{praticienId}/rdvs', 
        \apppraticien\application\actions\GetPraticienDispoAction::class)
        ->setName('getPraticienDispo');

        $app->get('/praticiens/{id}/weeks/{week}',
        \apppraticien\application\actions\GetRdvsByPraticienAction::class)
        ->setName('getRdvsByPraticien');
    $app->post('/praticiens/create',
        \apppraticien\application\actions\CreatePraticienAction::class);

    $app->get('/praticiens[/]',
        \apppraticien\application\actions\GetAllPraticiensAction::class)
        ->setName('getAllPraticiens');

    $app->get('/praticiens/{id}[/]',
        \apppraticien\application\actions\GetPraticienByIdAction::class)
        ->setName('getPraticienById');

    return $app;
};