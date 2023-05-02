<?php

use App\Controllers\TwigExtension;
use Psr\Container\ContainerInterface;

use Slim\Views\Twig;
use Twig\Extension\DebugExtension;
use Twig\Loader\FilesystemLoader;

return [
    Twig::class => function (ContainerInterface $container): Twig {
        $config = $container->get('config')['twig'];

        $loader = new FilesystemLoader($config['template_dirs']);

        $environment = new Twig($loader, [
            'cache' => $config['debug'] ? false : $config['cache_dir'],
            'debug' => $config['debug'],
            'strict_variables' => $config['debug'],
            'auto_reload' => $config['debug'],
        ]);

        if ($config['debug']) {
            $environment->addExtension(new DebugExtension());
        }
        $environment->addExtension(new TwigExtension());
        foreach ($config['extensions'] as $class) {
            $extension = $container->get($class);
            $environment->addExtension($extension);
        }

        return $environment;
    },
];
