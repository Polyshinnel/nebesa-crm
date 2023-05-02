<?php

use App\Models\Database;

require __DIR__ . '/../vendor/autoload.php';

$container = require __DIR__ . '/../bootstrap/container.php';

ini_set('display_errors', $container->get('dev_mode') ? 'On' : 'Off'); // сообщения с ошибками будут показываться
error_reporting($container->get('dev_mode') ? E_ALL : 0); // E_ALL - отображаем ВСЕ ошибки

$container->get(Database::class);

$app = (require __DIR__.'/../bootstrap/app.php')($container);

$app->run();