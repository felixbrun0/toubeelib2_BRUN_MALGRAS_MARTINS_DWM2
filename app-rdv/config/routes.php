<?php
declare(strict_types=1);

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;


return function( \Slim\App $app):\Slim\App {

//    $app->add(\toubeelib\application\middlewares\Cors::class);

    $app->options('/{routes:.+}', function (Request $rq, Response $rs, array $args): Response {
        return $rs;
    });

    $app->get('/',
        \apprdv\application\actions\HomeAction::class);

    $app->get('/rdvs[/]',
        \apprdv\application\actions\GetAllRdvsAction::class)
        ->setName('getAllRdvs');

    $app->get('/rdvs/{id}[/]',
        \apprdv\application\actions\GetRdvAction::class)
        ->setName('getRdv');

    $app->post('/rdvs/create',
        \apprdv\application\actions\CreateRdvAction::class);

    $app->patch('/rdvs/{id}',
        \apprdv\application\actions\UpdateRdvAction::class)
        ->setName('updateRdv');
        
    $app->delete('/rdvs/{id}',
        \apprdv\application\actions\DeleteRdvAction::class)
        ->setName('deleteRdv');

    $app->get('/praticiens/{praticienId}/rdvs', 
        \apprdv\application\actions\GetPraticienDispoAction::class)
        ->setName('getPraticienDispo');

        $app->get('/praticiens/{id}/weeks/{week}',
        \apprdv\application\actions\GetRdvsByPraticienAction::class)
        ->setName('getRdvsByPraticien');
    return $app;
};