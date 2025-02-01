<?php
declare(strict_types=1);

use auth\actions\RegisterAction;
use auth\actions\SigninAction;
use auth\actions\RefreshTokenAction;
use auth\actions\ValidateTokenAction;
use DI\ContainerBuilder;
use Psr\Container\ContainerInterface;
use Slim\Factory\AppFactory;
use Slim\Psr7\Factory\ResponseFactory;
use Psr\Http\Message\ResponseFactoryInterface;

return [
    'settings' => [
        'displayErrorDetails' => true,
        'db' => [
            'driver' => 'pgsql',
            'host' => 'toubeelib.dbauth',
            'database' => 'auth',
            'username' => 'toubeelib',
            'password' => 'toubeelib',
            'port' => 5432,
            'charset' => 'utf8'
        ],
        'jwt' => [
            'secret' => 'toubeelib_jwt_secret_key_2024_secure_and_long_enough_for_production',
            'expires' => 3600,
            'refresh_expires' => 86400
        ]
    ],

    ResponseFactoryInterface::class => function (ContainerInterface $container) {
        return new ResponseFactory();
    },

    PDO::class => function (ContainerInterface $container) {
        $settings = $container->get('settings')['db'];
        $dsn = sprintf(
            '%s:host=%s;dbname=%s;port=%d',
            $settings['driver'],
            $settings['host'],
            $settings['database'],
            $settings['port']
        );

        return new PDO($dsn, $settings['username'], $settings['password'], [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ]);
    },

    RegisterAction::class => function (ContainerInterface $container) {
        return new RegisterAction($container->get(PDO::class));
    },

    SigninAction::class => function (ContainerInterface $container) {
        return new SigninAction($container->get(PDO::class), $container->get('settings')['jwt']);
    },

    RefreshTokenAction::class => function (ContainerInterface $container) {
        return new RefreshTokenAction($container->get(PDO::class), $container->get('settings')['jwt']);
    },

    ValidateTokenAction::class => function (ContainerInterface $container) {
        return new ValidateTokenAction($container->get('settings')['jwt']);
    }
];
