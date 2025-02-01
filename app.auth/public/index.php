<?php
declare(strict_types=1);

require_once __DIR__ . '/../vendor/autoload.php';

use DI\ContainerBuilder;
use Slim\Factory\AppFactory;

$builder = new ContainerBuilder();
$builder->addDefinitions(__DIR__ . '/../config/dependencies.php');
$container = $builder->build();

$app = AppFactory::createFromContainer($container);

(require_once __DIR__ . '/../config/routes.php')($app);
(require_once __DIR__ . '/../config/middleware.php')($app);

$app->run();
