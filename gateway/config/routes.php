<?php
declare(strict_types=1);

use gateway\application\actions\GenericActionRdv;
use Slim\App;

return function (App $app): App {
//    $app->get('/praticiens', GenericActionRdv::class)->setName('getAllPraticiens');
//    $app->get('/praticiens/{id}', GenericActionRdv::class)->setName('getPraticienById');
    //route générique qui appel GenericActionRdv
//        $app->get('/{entity}[/{id}]', GenericActionRdv::class)->setName('genericAction');

    $app->get('/rdvs[/]',
        GenericActionRdv::class)
        ->setName('getAllRdvs');

    $app->get('/rdvs/{id}[/]',
        GenericActionRdv::class)
        ->setName('getRdv');

    $app->post('/rdvs/create',
        GenericActionRdv::class)
        ->setName('createRdv');

    $app->patch('/rdvs/{id}',
        GenericActionRdv::class)
        ->setName('updateRdv');

    $app->delete('/rdvs/{id}',
        GenericActionRdv::class)
        ->setName('deleteRdv');

    $app->get('/praticiens/{praticienId}/rdvs',
        GenericActionRdv::class)
        ->setName('getPraticienDispo');

    $app->get('/praticiens/{id}/weeks/{week}',
        GenericActionRdv::class)
        ->setName('getRdvsByPraticien');

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