<?php

namespace App\Pages\Telegram;

use App\Controllers\Telegram\TelegramTask;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Psr7\Factory\StreamFactory;
use Slim\Psr7\Headers;
use Slim\Psr7\Response;
use Slim\Views\Twig;

class TelegramPage
{
    private Twig $twig;
    private TelegramTask $telegramTask;

    public function __construct(Twig $twig, TelegramTask $telegramTask)
    {
        $this->twig = $twig;
        $this->telegramTask = $telegramTask;
    }

    public function get(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $err = false;
        $params = $request->getQueryParams();
        if(isset($params['err'])) {
            $err = $params['err'];
        }
        $data = $this->twig->fetch('telegram/auth.twig', [
            'title' => 'Авторизация',
            'err' => $err
        ]);
        return new Response(
            200,
            new Headers(['Content-Type' => 'text/html']),
            (new StreamFactory())->createStream($data)
        );
    }

    public function getNewTasks(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $params = $request->getQueryParams();
        if(isset($params['user_id'])) {
            $userId = $params['user_id'];
            $menu = $this->telegramTask->generateMenu($userId);
            $baseUrl = '/telegram/new-tasks?user_id='.$userId;
            $data = $this->twig->fetch('telegram/task-template.twig', [
                'title' => 'Новые задачи',
                'menu' => $menu,
                'base_url' => $baseUrl
            ]);
            return new Response(
                200,
                new Headers(['Content-Type' => 'text/html']),
                (new StreamFactory())->createStream($data)
            );
        }
        return $response->withHeader('Location','/telegram')->withStatus(302);
    }

    public function processTasks(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $params = $request->getQueryParams();
        if(isset($params['user_id'])) {
            $userId = $params['user_id'];
            $menu = $this->telegramTask->generateMenu($userId);
            $baseUrl = '/telegram/new-tasks?user_id='.$userId;

            $data = $this->twig->fetch('telegram/task-template.twig', [
                'title' => 'Принятые задачи',
                'menu' => $menu,
                'base_url' => $baseUrl
            ]);
            return new Response(
                200,
                new Headers(['Content-Type' => 'text/html']),
                (new StreamFactory())->createStream($data)
            );
        }


        return $response->withHeader('Location','/telegram')->withStatus(302);
    }

    public function successTasks(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $params = $request->getQueryParams();
        if(isset($params['user_id'])) {
            $userId = $params['user_id'];
            $menu = $this->telegramTask->generateMenu($userId);
            $baseUrl = '/telegram/new-tasks?user_id='.$userId;

            $data = $this->twig->fetch('telegram/task-template.twig', [
                'title' => 'На проверке',
                'menu' => $menu,
                'base_url' => $baseUrl
            ]);
            return new Response(
                200,
                new Headers(['Content-Type' => 'text/html']),
                (new StreamFactory())->createStream($data)
            );
        }
        return $response->withHeader('Location','/telegram')->withStatus(302);

    }

    public function checkUser(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $params = $request->getParsedBody();
        $msg = ['err' => 'not found data'];

        if(isset($params['telegram_id'])) {
            $result = $this->telegramTask->getTgUser($params['telegram_id']);
            if(!empty($result)) {
                $msg = [
                    'user_id' => $result,
                    'err' => 'none'
                ];
            }
        }

        $data = json_encode($msg);

        return new Response(
            200,
            new Headers(['Content-Type' => 'application/json']),
            (new StreamFactory())->createStream($data)
        );
    }

    public function authUser(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $params = $request->getParsedBody();
        $login = $params['login'];
        $password = $params['pass'];
        $telegram_id = $params['telegram_id'];

        $result = $this->telegramTask->authUser($login, $password, $telegram_id);
        if(!empty($result)) {
            $userId = $result;
            $url = '/telegram/new-tasks?user_id='.$userId;
            return $response->withHeader('Location',$url)->withStatus(302);
        }

        return $response->withHeader('Location','/telegram?err=true')->withStatus(302);
    }
}