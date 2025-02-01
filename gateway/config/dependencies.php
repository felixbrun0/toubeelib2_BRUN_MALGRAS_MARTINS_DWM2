<?php

use DI\Definition\Source\DefinitionFile;
use gateway\application\actions\GenericActionPraticien;
use gateway\application\actions\GenericActionAuth;
use gateway\application\actions\GetPraticienByIdAction;
use gateway\application\actions\GenericActionRdv;
use gateway\application\middlewares\AuthMiddleware;
use GuzzleHttp\Client;
use Psr\Container\ContainerInterface;
use function DI\value;

return [
    'displayErrorDetails' => true,
    'auth.service.url' => value('http://auth.toubeelib:80'),
    'rdv.service.url' => value('http://rdv.toubeelib:80'),
    'praticien.service.url' => value('http://praticien.toubeelib:80'),

    Client::class => function () {
        return new Client();
    },

    'clientRdv' => function (ContainerInterface $c) {
        return new Client([
            'base_uri' => $c->get('rdv.service.url'),
            'timeout' => 5.0,
            'connect_timeout' => 5.0,
        ]);
    },

    'clientPraticien' => function (ContainerInterface $c) {
        return new Client([
            'base_uri' => $c->get('praticien.service.url'),
            'timeout' => 5.0,
            'connect_timeout' => 5.0,
        ]);
    },

    'clientAuth' => function (ContainerInterface $c) {
        return new Client([
            'base_uri' => $c->get('auth.service.url'),
            'timeout' => 5.0,
            'connect_timeout' => 5.0,
        ]);
    },

    GenericActionAuth::class => function (ContainerInterface $c) {
        return new GenericActionAuth($c->get('clientAuth'), $c->get('auth.service.url'));
    },

    GenericActionPraticien::class => function (ContainerInterface $c) {
        return new GenericActionPraticien($c->get('clientPraticien'));
    },

    'gateway\application\actions\GenericActionRdv' => function (ContainerInterface $c) {
        return new \gateway\application\actions\GenericActionRdv($c->get('clientRdv'));
    },

    AuthMiddleware::class => function (ContainerInterface $c) {
        return new AuthMiddleware($c->get('clientAuth'), $c->get('auth.service.url'));
    }
];