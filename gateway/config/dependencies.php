<?php

use gateway\application\actions\GenericActionPraticien;
use Psr\Container\ContainerInterface;
use GuzzleHttp\Client;
use gateway\application\actions\GetPraticienByIdAction;

return [
    'displayErrorDetails' => true,
//    Client::class => function (ContainerInterface $c) {
//        return new Client([
//            'base_uri' => 'http://api.toubeelib',
//            'timeout' => 2.0,
//            'connect_timeout' => 2.0,
//        ]);
//    },
//    //creer un client avec le port 6088 pour GenericActionRdv et 6089 pour GenericActionPatient
    'clientRdv' => function (ContainerInterface $c) {
        return new Client([
            'base_uri' => 'http://rdv.toubeelib',
            'timeout' => 2.0,
            'connect_timeout' => 2.0,
        ]);
    },
    'clientPraticien' => function (ContainerInterface $c) {
        return new Client([
            'base_uri' => 'http://praticien.toubeelib',
            'timeout' => 2.0,
            'connect_timeout' => 2.0,
        ]);
    },

    \gateway\application\actions\GenericActionRdv::class => function (ContainerInterface $c) {
        return new \gateway\application\actions\GenericActionRdv($c->get('clientRdv'));
    },

    GenericActionPraticien::class => function (ContainerInterface $c) {
        return new GenericActionPraticien($c->get('clientPraticien'));
    },
];