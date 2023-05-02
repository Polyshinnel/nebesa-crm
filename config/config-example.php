<?php

use Psr\Container\ContainerInterface;
use function DI\factory;
use App\Models\Database;

return [
    Database::class => factory(function (ContainerInterface $container) {
        return new Database($container->get('config')['db_settings']);
    }) ,
    'dev_mode' => true,
    'settings' => [
        'displayErrorDetails' => factory(function (ContainerInterface $container){
            return $container->get('dev_mode');
        })
    ],
    'config' => [
        'db_settings' => [
            'db_driver' => 'mysql',
            'db_host' => 'localhost',
            'db_name' => 'dbName',
            'db_user' => 'dbUser',
            'db_pass' => 'dbPass',
            'db_port' => '3306'
        ],
        'twig' => [
            'debug' => true,
            'template_dirs' => __DIR__ . '/../templates',
            'cache_dir' => __DIR__ . '/../tmp/cache/twig',
            'extensions' => [],
        ],
    ]
];