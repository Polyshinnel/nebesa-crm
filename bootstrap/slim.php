<?php

use Psr\Container\ContainerInterface;
use Slim\CallableResolver;
use Slim\Interfaces\CallableResolverInterface;
use Psr\Http\Message\ResponseFactoryInterface;
use Slim\Psr7\Factory\ResponseFactory;

return [
    CallableResolverInterface::class => static function (ContainerInterface $container): CallableResolverInterface {
        return new CallableResolver($container);
    },
    ResponseFactoryInterface::class => static function (): ResponseFactoryInterface {
        return new ResponseFactory();
    },
];