<?php

use Psr\Container\ContainerInterface;
use Slim\App;
use Slim\Factory\AppFactory;

return static function (ContainerInterface $container) : App {
    $app = AppFactory::createFromContainer($container);
    (require __DIR__.'/middleware.php')($app);
    (require __DIR__.'/http-routes.php')($app);
    return $app;
};