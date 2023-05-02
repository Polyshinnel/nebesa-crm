<?php

use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseFactoryInterface;
use Slim\Interfaces\CallableResolverInterface;
use Slim\Middleware\ErrorMiddleware;

return [
    ErrorMiddleware::class => static function (ContainerInterface $container): ErrorMiddleware {

        $callableResolver = $container->get(CallableResolverInterface::class);
        $responseFactory = $container->get(ResponseFactoryInterface::class);

        return new ErrorMiddleware(
            $callableResolver,
            $responseFactory,
            $container->get('dev_mode'),
            true,
            true
        );
    },
];