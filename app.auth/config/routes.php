<?php
declare(strict_types=1);

use auth\actions\RegisterAction;
use auth\actions\SigninAction;
use auth\actions\RefreshTokenAction;
use auth\actions\ValidateTokenAction;
use Slim\App;

return function (App $app): void {
    $app->post('/register', RegisterAction::class);
    $app->post('/signin', SigninAction::class);
    $app->post('/refresh', RefreshTokenAction::class);
    $app->post('/tokens/validate', ValidateTokenAction::class);
};
