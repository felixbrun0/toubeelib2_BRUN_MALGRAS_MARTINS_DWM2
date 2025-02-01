<?php

use DI\ContainerBuilder;
use apprdv\application\middlewares\AuthorizationMiddleware;
use Psr\Container\ContainerInterface;
use apprdv\application\actions\CreateRdvAction;
use apprdv\application\actions\DeleteRdvAction;
use apprdv\application\actions\GetAllRdvsAction;
use apprdv\application\actions\GetRdvAction;
use apprdv\application\actions\GetRdvsByPraticienAction;
use apprdv\application\actions\UpdateRdvAction;
use apprdv\application\actions\GetPraticienDispoAction;
use apprdv\core\services\praticien\ServicePraticien;
use apprdv\core\services\rdv\ServiceRdv;
use apprdv\infrastructure\db\PDOPraticienRepository;

return [
    'settings' => [
        'jwt_secret' => 'votre_secret_jwt_ici', // À remplacer par votre vrai secret
        'displayErrorDetails' => true,
        'logErrors' => true,
        'logErrorDetails' => true,
    ],

    AuthorizationMiddleware::class => function (ContainerInterface $c) {
        $settings = $c->get('settings');
        return new AuthorizationMiddleware($settings['jwt_secret']);
    },

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
    'rdv.pdo' => function (ContainerInterface $container) {
        $config = parse_ini_file(__DIR__ . '/config.ini');
        $dsn = "{$config['driver']}:host={$config['host2']};dbname={$config['dbrdvs']}";
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

    'logger.service.rdv' => function(\Psr\Container\ContainerInterface $container) {
        return new ServiceRdv($container->get('logger.rdv'), $container->get('logger.service.praticien'));
    },

    'logger.rdv' => function (ContainerInterface $container) {
        return new \apprdv\infrastructure\db\PDORdvRepository($container->get('rdv.pdo'));
    },

    GetRdvAction::class => function (\Psr\Container\ContainerInterface $container) {
        return new GetRdvAction($container->get('logger.rdv'));
    },
    DeleteRdvAction::class => function (ContainerInterface $container) {
        return new DeleteRdvAction($container->get('logger.rdv'));
    },
    CreateRdvAction::class => function (\Psr\Container\ContainerInterface $container) {
        return new CreateRdvAction($container->get('logger.rdv'));
    },
    GetAllRdvsAction::class => function (\Psr\Container\ContainerInterface $container) {
        return new GetAllRdvsAction($container->get('logger.service.rdv'));
    },
    UpdateRdvAction::class => function (\Psr\Container\ContainerInterface $container) {
        return new UpdateRdvAction($container->get('logger.rdv'));
    },
    GetPraticienDispoAction::class => function (\Psr\Container\ContainerInterface $container) {
        return new GetPraticienDispoAction($container->get('logger.rdv'));
    },
    GetRdvsByPraticienAction::class => function (\Psr\Container\ContainerInterface $container) {
        return new GetRdvsByPraticienAction($container->get('logger.service.rdv'));
    },
    \apprdv\infrastructure\db\PDORdvRepository::class => function (\Psr\Container\ContainerInterface $container) {
        return new \apprdv\infrastructure\db\PDORdvRepository($container->get('rdv.pdo'));
    },
];