<?php

namespace App\Pages;

use App\Controllers\HeaderController;
use App\Controllers\StagesController;
use App\Controllers\Tasks\TaskController;
use App\Repository\FunnelRepository;
use App\Repository\UserRepository;
use App\Utils\ToolClass;
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
    private TaskController $taskController;
    private ToolClass $toolClass;
    private UserRepository $userRepository;

    public function __construct(
        Twig $twig,
        HeaderController $headerController,
        TaskController $taskController,
        ToolClass $toolClass,
        UserRepository $userRepository
    )
    {
        $this->twig = $twig;
        $this->headerController = $headerController;
        $this->taskController = $taskController;
        $this->toolClass = $toolClass;
        $this->userRepository = $userRepository;
    }

    public function get(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {

        $login = trim($_COOKIE["user"]);
        $headerData = $this->headerController->getHeaderData($login);
        $dataList = $this->taskController->getTaskList();
        $allUsers = $this->userRepository->getAllUsers();

        $data = $this->twig->fetch('Tasks.twig', [
            'title' => 'Задачи',
            'userName' => $headerData['name'],
            'avatar' => $headerData['avatar'],
            'funnelSwitch' => false,
            'workAreaTitle' => 'Задачи',
            'data_list' => $dataList,
            'users' => $allUsers
        ]);
        return new Response(
            200,
            new Headers(['Content-Type' => 'text/html']),
            (new StreamFactory())->createStream($data)
        );
    }

    public function getTask(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $login = trim($_COOKIE["user"]);
        $headerData = $this->headerController->getHeaderData($login);
        $taskId = $args['id'];

        $taskInfo = $this->taskController->getTask($taskId);
        $taskInfo['id'] = $taskId;
        $allUsers = $this->userRepository->getAllUsers();

        $data = $this->twig->fetch('task.twig', [
            'title' => $taskInfo['task_name'],
            'userName' => $headerData['name'],
            'avatar' => $headerData['avatar'],
            'funnelSwitch' => false,
            'workAreaTitle' => $taskInfo['task_name'],
            'task' => $taskInfo,
            'stages' => $taskInfo['stages'],
            'users' => $allUsers
        ]);
        return new Response(
            200,
            new Headers(['Content-Type' => 'text/html']),
            (new StreamFactory())->createStream($data)
        );
    }

    public function createTask(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $login = trim($_COOKIE["user"]);
        $headerData = $this->headerController->getHeaderData($login);
        $userId = $headerData['id'];

        $params = $request->getParsedBody();
        $executorId = $params['executor'];
        $expiredDate = $params['date_end'];
        $reformatDate = $this->toolClass->reformatDate($expiredDate, 'en');

        $taskName = $params['task-name'];
        $taskText = $params['task-text'];
        $taskId = $this->taskController->createTask($executorId, $userId, $taskName, $taskText, $reformatDate);
        $url = '/tasks/task/'.$taskId;
        return $response->withHeader('Location',$url)->withStatus(302);
    }

    public function updateTaskStage(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $params = $request->getParsedBody();
        $taskId = $params['task_id'];
        $stageId = $params['stage_id'];

        $login = trim($_COOKIE["user"]);
        $headerData = $this->headerController->getHeaderData($login);
        $userId = $headerData['id'];

        $this->taskController->updateStageTask($taskId, $stageId, $userId);
        $message = ['msg' => 'stage was changed'];
        $json = json_encode($message);
        return new Response(
            200,
            new Headers(['Content-Type' => 'application/json']),
            (new StreamFactory())->createStream($json)
        );
    }

    public function updateTask(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $params = $request->getParsedBody();
        $taskId = $params['task_id'];
        $executorId = $params['executor_id'];
        $expiredDate = $params['expired_date'];
        $reformatDate = $this->toolClass->reformatDate($expiredDate, 'en');

        $login = trim($_COOKIE["user"]);
        $headerData = $this->headerController->getHeaderData($login);
        $userId = $headerData['id'];

        $this->taskController->updateTask($executorId, $reformatDate, $taskId, $userId);
        $message = ['msg' => 'task was updated'];
        $json = json_encode($message);
        return new Response(
            200,
            new Headers(['Content-Type' => 'application/json']),
            (new StreamFactory())->createStream($json)
        );
    }

    public function createTaskMessage(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $login = trim($_COOKIE["user"]);
        $headerData = $this->headerController->getHeaderData($login);
        $userId = $headerData['id'];

        $params = $request->getParsedBody();
        $text = $params['text'];
        $taskId = $params['task_id'];

        $this->taskController->createMessage($userId, $text, $taskId);

        $message = ['msg' => 'message was added'];
        $json = json_encode($message);
        return new Response(
            200,
            new Headers(['Content-Type' => 'application/json']),
            (new StreamFactory())->createStream($json)
        );
    }
}