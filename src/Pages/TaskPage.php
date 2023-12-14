<?php

namespace App\Pages;

use App\Controllers\HeaderController;
use App\Controllers\StagesController;
use App\Repository\FunnelRepository;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Psr7\Factory\StreamFactory;
use Slim\Psr7\Headers;
use Slim\Psr7\Response;
use Slim\Views\Twig;

class TaskPage
{
    private Twig $twig;
    private HeaderController $headerController;


    public function __construct(
        Twig $twig,
        HeaderController $headerController)
    {
        $this->twig = $twig;
        $this->headerController = $headerController;

    }

    public function get(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {

        $login = trim($_COOKIE["user"]);
        $headerData = $this->headerController->getHeaderData($login);


        $data = $this->twig->fetch('Tasks.twig', [
            'title' => 'Задачи',
            'userName' => $headerData['name'],
            'avatar' => $headerData['avatar'],
            'funnelSwitch' => false,
            'workAreaTitle' => 'Задачи',
        ]);
        return new Response(
            200,
            new Headers(['Content-Type' => 'text/html']),
            (new StreamFactory())->createStream($data)
        );
    }

    public function getTask(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $login = trim($_COOKIE["user"]);
        $headerData = $this->headerController->getHeaderData($login);

        $task = [
            'task_name' => 'Починить синхрофазатрон',
            'stage_color' => 'base',
            'stage_name' => 'Новая задача',
            'events' => []
        ];

        $stages = [
            [
                'id' => '1',
                'color_class' => 'base',
                'name' => 'Новая задача'
            ],
            [
                'id' => '2',
                'color_class' => 'stage-2',
                'name' => 'Задача выполняется'
            ],
            [
                'id' => '3',
                'color_class' => 'stage-3',
                'name' => 'Сдана на проверку'
            ],
        ];

        $data = $this->twig->fetch('task.twig', [
            'title' => 'Починить синхрофазатрон',
            'userName' => $headerData['name'],
            'avatar' => $headerData['avatar'],
            'funnelSwitch' => false,
            'workAreaTitle' => 'Починить синхрофазатрон',
            'task' => $task,
            'stages' => $stages
        ]);
        return new Response(
            200,
            new Headers(['Content-Type' => 'text/html']),
            (new StreamFactory())->createStream($data)
        );
    }
}