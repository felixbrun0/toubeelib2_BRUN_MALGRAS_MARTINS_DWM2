<?php

use Psr\Container\ContainerInterface;
use apppraticien\application\actions\CreatePraticienAction;
use apppraticien\application\actions\GetAllPraticiensAction;
use apppraticien\application\actions\GetPraticienByIdAction;
use apppraticien\application\actions\GetRdvsByPraticienAction;
use apppraticien\application\actions\GetPraticienDispoAction;
use apppraticien\core\services\praticien\ServicePraticien;
use apppraticien\infrastructure\db\PDOPraticienRepository;


return [

    'praticien.pdo' => function (ContainerInterface $container) {
        $config = parse_ini_file(__DIR__ . '/config.ini');
        $dsn = "{$config['driver']}:host={$config['host1']};dbname={$config['dbpraticien']}";
        $user = $config['user'];
        $password = $config['password'];
        try {
            $pdo = new \PDO($dsn, $user, $password);
            $pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
            return $pdo;
        } catch (\PDOException $e) {
            throw new \RuntimeException('Connexion échouée : ' . $e->getMessage());
        }
    },

    'logger.service.praticien' => function(\Psr\Container\ContainerInterface $container) {
        return new ServicePraticien($container->get('logger.praticien'));
    },

    'logger.praticien' => function (ContainerInterface $container) {
        return new PDOPraticienRepository($container->get('praticien.pdo'));
    },

    GetPraticienDispoAction::class => function (\Psr\Container\ContainerInterface $container) {
        return new GetPraticienDispoAction($container->get('logger.rdv'));
    },
    GetRdvsByPraticienAction::class => function (\Psr\Container\ContainerInterface $container) {
            return new GetRdvsByPraticienAction($container->get('logger.service.rdv'));
    },

    PDOPraticienRepository::class => function (\Psr\Container\ContainerInterface $container) {
        return new PDOPraticienRepository($container->get('praticien.pdo'));
    },
    CreatePraticienAction::class => function (\Psr\Container\ContainerInterface $container) {
        return new CreatePraticienAction($container->get('logger.service.praticien'));
    },
    GetPraticienByIdAction::class => function (\Psr\Container\ContainerInterface $container) {
        return new GetPraticienByIdAction($container->get('logger.service.praticien'));
    },
    GetAllPraticiensAction::class => function (\Psr\Container\ContainerInterface $container) {
        return new GetAllPraticiensAction($container->get('logger.service.praticien'));
    },
];