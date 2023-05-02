<?php

use Slim\App;
use Slim\Middleware\ErrorMiddleware;

return static function (App $app): void {
    $app->addBodyParsingMiddleware();
    $app->addRoutingMiddleware();
    $app->add(ErrorMiddleware::class);
};