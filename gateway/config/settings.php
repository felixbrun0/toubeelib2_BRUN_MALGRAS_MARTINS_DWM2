<?php

use function DI\value;

return [
    'displayErrorDetails' => value(true),
    'logs.dir' => value(__DIR__ . '/../var/logs'),
    'auth.service.url' => value('http://auth.toubeelib:80'),
];
