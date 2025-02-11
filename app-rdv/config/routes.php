<?php
declare(strict_types=1);

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use apprdv\application\middlewares\AuthorizationMiddleware;

return function( \Slim\App $app):\Slim\App {

//    $app->add(\toubeelib\application\middlewares\Cors::class);

    $app->options('/{routes:.+}', function (Request $rq, Response $rs, array $args): Response {
        return $rs;
    });

    $app->get('/',
        \apprdv\application\actions\HomeAction::class);

    $app->group('/rdvs', function() use ($app) {
        $app->get('[/]',
            \apprdv\application\actions\GetAllRdvsAction::class)
            ->setName('getAllRdvs');

        $app->get('/{id}[/]',
            \apprdv\application\actions\GetRdvAction::class)
            ->setName('getRdv');

        $app->post('/create',
            \apprdv\application\actions\CreateRdvAction::class);

        $app->patch('/{id}',
            \apprdv\application\actions\UpdateRdvAction::class)
            ->setName('updateRdv');
            
        $app->delete('/{id}',
            \apprdv\application\actions\DeleteRdvAction::class)
            ->setName('deleteRdv');
    })->add(AuthorizationMiddleware::class);

    $app->group('/praticiens', function() use ($app) {
        $app->get('/{praticienId}/rdvs', 
            \apprdv\application\actions\GetPraticienDispoAction::class)
            ->setName('getPraticienDispo');

        $app->get('/{id}/weeks/{week}',
            \apprdv\application\actions\GetRdvsByPraticienAction::class)
            ->setName('getRdvsByPraticien');
    })->add(AuthorizationMiddleware::class);

    return $app;
};