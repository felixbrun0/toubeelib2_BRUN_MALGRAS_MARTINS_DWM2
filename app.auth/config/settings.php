<?php
declare(strict_types=1);

return [
    'displayErrorDetails' => true,
    'jwt' => [
        'secret' => 'votre_secret_key',
        'expires' => 3600,
        'refresh_expires' => 86400
    ],
    'db' => [
        'driver' => 'pgsql',
        'host' => 'toubeelib.dbauth',
        'database' => 'toubeelib',
        'username' => 'toubeelib',
        'password' => 'toubeelib',
        'charset' => 'utf8',
        'collation' => 'utf8_unicode_ci',
        'prefix' => ''
    ]
];
