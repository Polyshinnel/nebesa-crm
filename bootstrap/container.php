<?php

use DI\ContainerBuilder;

$builder = new ContainerBuilder();

$builder->addDefinitions(require __DIR__.'/../config/config.php');
$builder->addDefinitions(require  __DIR__.'/../bootstrap/twig.php');
$builder->addDefinitions(require  __DIR__.'/../bootstrap/slim.php');
$builder->addDefinitions(require __DIR__.'/../bootstrap/errors.php');
$builder->addDefinitions(require __DIR__.'/../bootstrap/console.php');

return $builder->build();