<?php
declare(strict_types=1);

use gateway\application\actions\GenericActionRdv;
use gateway\application\actions\GenericActionAuth;
use gateway\application\actions\GenericActionPraticien;
use gateway\application\middlewares\AuthMiddleware;
use Slim\App;

return function (App $app): App {
    // Routes d'authentification
    $app->post('/register', GenericActionAuth::class)->setName('register');
    $app->post('/signin', GenericActionAuth::class)->setName('signin');
    $app->post('/refresh', GenericActionAuth::class)->setName('refresh');
    $app->post('/tokens/validate', GenericActionAuth::class)->setName('validateToken');

    //route générique qui appel GenericActionRdv
//        $app->get('/{entity}[/{id}]', GenericActionRdv::class)->setName('genericAction');

    $app->group('/rdvs', function($group) {
        $group->get('[/]',
            GenericActionRdv::class)
            ->setName('getAllRdvs');

        $group->get('/{id}[/]',
            GenericActionRdv::class)
            ->setName('getRdv');

        $group->post('/create',
            GenericActionRdv::class)
            ->setName('createRdv');

        $group->patch('/{id}',
            GenericActionRdv::class)
            ->setName('updateRdv');

        $group->delete('/{id}',
            GenericActionRdv::class)
            ->setName('deleteRdv');

        $group->get('/praticiens/{praticienId}/rdvs',
            GenericActionRdv::class)
            ->setName('getPraticienDispo');

        $group->get('/praticiens/{id}/weeks/{week}',
            GenericActionRdv::class)
            ->setName('getRdvsByPraticien');
    })->add(AuthMiddleware::class);

    $app->post('/praticiens/create',
        \gateway\application\actions\GenericActionPraticien::class);

    $app->get('/praticiens[/]',
        \gateway\application\actions\GenericActionPraticien::class)
        ->setName('getAllPraticiens');

    $app->get('/praticiens/{id}[/]',
        \gateway\application\actions\GenericActionPraticien::class)
        ->setName('getPraticienById');
    return $app;
};