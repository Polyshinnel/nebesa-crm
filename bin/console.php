<?php

use App\Models\Database;
use Symfony\Component\Console\Application;

require __DIR__.'/../vendor/autoload.php';

$container = require __DIR__ . '/../bootstrap/container.php';
$container->get(Database::class);
$application = new Application();
$commands = $container->get('console')['commands'];

foreach ($commands as $name)
{
    $command = $container->get($name);
    $application->add($command);
}
$application->run();