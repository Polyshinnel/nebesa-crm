<?php

$container = require __DIR__ . '/bootstrap/container.php';
$dbSettings = $container->get('config')['db_settings'];

return [
    'paths' => [
        'migrations' => 'src/Migration'
    ],
    'migration_base_class' => '\App\Migration\Migration',
    'environments' => [
        'default_migration_table' => 'phinxlog',
        'default_database' => 'dev',
        'dev' => [
            'adapter' => $dbSettings['db_driver'],
            'host' => $dbSettings['db_host'],
            'name' => $dbSettings['db_name'],
            'user' => $dbSettings['db_user'],
            'pass' => $dbSettings['db_pass'],
            'port' => $dbSettings['db_port']
        ]
    ]
];