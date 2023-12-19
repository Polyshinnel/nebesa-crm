<?php

namespace App\Controllers\Tasks;

use App\Controllers\Telegram\TelegramMessages;
use App\Repository\Tasks\TaskEventRepository;
use App\Repository\Tasks\TaskRepository;
use App\Repository\Tasks\TaskStageRepository;
use App\Repository\UserRepository;
use App\Utils\ToolClass;

class TaskController
{
    private TaskRepository $taskRepository;
    private TaskEventRepository $eventRepository;
    private TaskStageRepository $taskStageRepository;
    private UserRepository $userRepository;
    private TaskEventController $taskEventController;
    private ToolClass $toolClass;
    private TelegramMessages $telegramMessages;

    public function __construct(
        TaskRepository $taskRepository,
        TaskEventRepository $eventRepository,
        TaskStageRepository $taskStageRepository,
        UserRepository  $userRepository,
        TaskEventController $taskEventController,
        ToolClass $toolClass,
        TelegramMessages $telegramMessages
    )
    {
        $this->taskRepository = $taskRepository;
        $this->eventRepository = $eventRepository;
        $this->taskStageRepository = $taskStageRepository;
        $this->userRepository = $userRepository;
        $this->taskEventController = $taskEventController;
        $this->toolClass = $toolClass;
        $this->telegramMessages = $telegramMessages;
    }

    public function createTask($executorId, $controllerId, $taskTitle, $taskText, $expiredDate): string {
        $createArr = [
            'stage_id' => 1,
            'executor_id' => $executorId,
            'controller_id' => $controllerId,
            'task_title' => $taskTitle,
            'date_create' => date('Y-m-d H:i:s'),
            'expired_date' => $expiredDate
        ];

        $taskId = $this->taskRepository->createTask($createArr);
        $params = [
            'user_id' => $controllerId,
            'executor_id' => $executorId,
        ];
        $systemText = $this->taskEventController->textCollector('create task', $params);
        $this->taskEventController->createEvent($taskId, 'system', $systemText, $controllerId);

        $controllerData = $this->userRepository->getFilteredUsers(['id' => $controllerId]);
        $executorData = $this->userRepository->getFilteredUsers(['id' => $executorId]);
        $executorTgInfo = $executorData[0]['telegram_id'];
        $controllerName = $controllerData[0]['fullname'];
        $expiredDate = $this->toolClass->reformatDate($expiredDate);

        $tgMessage = $this->telegramMessages->createMessage($controllerName, $taskTitle, $taskText, $expiredDate);
        $this->telegramMessages->sendMessage($executorTgInfo, $tgMessage, 'Task', $taskId, $executorId);

        $this->taskEventController->createEvent($taskId, 'message', $taskText, $controllerId);
        return $taskId;
    }

    public function getTaskList(): ?array {
        $stages = $this->taskStageRepository->getVisibleStages();
        $totalInfo = [];

        foreach ($stages as $stage) {
            $stageId = $stage['id'];
            $tasks = $this->getTasks($stageId);
            $stage['tasks'] = $tasks;
            $totalInfo[] = $stage;
        }

        return $totalInfo;
    }

    public function getTasks($stageId): ?array {
        $selectArr = [
            'stage_id' => $stageId
        ];

        $result = $this->taskRepository->getTasks($selectArr);
        $tasks = [];
        if(!empty($result)) {
            foreach ($result as $item) {
                $taskId = $item['id'];
                $messages = $this->taskEventController->getMessages($taskId);
                $item['messages'] = count($messages);
                $executorData = $this->userRepository->getFilteredUsers(['id' => $item['executor_id']]);
                $controllerData = $this->userRepository->getFilteredUsers(['id' => $item['controller_id']]);
                $item['executor_name'] = $executorData[0]['fullname'];
                $item['controller_name'] = $controllerData[0]['fullname'];
                $reformatDate = $this->toolClass->reformatDate($item['expired_date']);
                $item['reformat_date'] = $reformatDate;
                $tasks[] = $item;

            }
        }
        return $tasks;
    }

    public function getTask($taskId): ?array {
        $selectArr = [
            'id' => $taskId
        ];
        $taskResult = $this->taskRepository->getTasks($selectArr);
        if(!empty($taskResult)) {
            $taskData = $taskResult[0];
            $events = $this->eventRepository->selectEvents(['task_id' => $taskId]);
            $stageInfo = $this->taskStageRepository->selectStages(['id' => $taskData['stage_id']]);
            $stages = $this->taskStageRepository->getVisibleStages();

            $executorData = $this->userRepository->getFilteredUsers(['id' => $taskData['executor_id']]);
            $controllerData = $this->userRepository->getFilteredUsers(['id' => $taskData['controller_id']]);

            $modEvents = [];

            if(!empty($events)) {
                foreach ($events as $event) {
                    $userId = $event['user_id'];
                    $userData = $this->userRepository->getFilteredUsers(['id' => $userId]);
                    $userName = $userData[0]['fullname'];
                    $event['username'] = $userName;
                    $dateArr = explode(' ', $event['date_create']);
                    $event['reformat_date'] = $this->toolClass->reformatDate($dateArr[0]).' '.$dateArr[1];
                    $modEvents[] = $event;
                }
            }


            return [
                'task_id' => $taskData['id'],
                'executor_id' => $taskData['executor_id'],
                'stage_id' => $taskData['stage_id'],
                'task_name' => $taskData['task_title'],
                'stage_name' => $stageInfo[0]['name'],
                'stage_color' => $stageInfo[0]['color_class'],
                'expired_date' => $this->toolClass->reformatDate($taskData['expired_date']),
                'executor_name' => $executorData[0]['fullname'],
                'controller_name' => $controllerData[0]['fullname'],
                'events' => $modEvents,
                'stages' => $stages
            ];
        }
        return NULL;
    }

    public function updateTask($executorUser, $expiredDate, $taskId, $userId): void {
        $updateArr = [
            'executor_id' => $executorUser,
            'expired_date' => $expiredDate
        ];

        $this->taskRepository->updateTask($updateArr, $taskId);
        $params = [
            'user_id' => $userId,
            'executor_id' => $executorUser,
            'expired_date' => $expiredDate
        ];
        $text = $this->taskEventController->textCollector('update task', $params);
        $this->taskEventController->createEvent($taskId, 'system', $text, $userId);
    }

    public function updateStageTask($taskId, $stageId, $userId): void {
        $updateArr = [
            'stage_id' => $stageId
        ];

        $this->taskRepository->updateTask($updateArr, $taskId);
        $params = [
            'user_id' => $userId,
            'stage_id' => $stageId
        ];
        $text = $this->taskEventController->textCollector('change stage', $params);
        $this->taskEventController->createEvent($taskId, 'system', $text, $userId);
    }

    public function createMessage($userId, $message, $taskId) {
        $this->taskEventController->createEvent($taskId, 'message', $message, $userId);
    }

    public function getFilteredTasks($userId, $stageId): ?array {
        $selectArr = [
            'executor_id' => $userId,
            'stage_id' => $stageId
        ];
        $rawTasks = $this->taskRepository->getTasks($selectArr);
        $stageClass = 'text-gray-600';
        if($stageId == 2) {
            $stageClass = 'text-blue-600';
        }
        if($stageId == 3) {
            $stageClass = 'text-green-600';
        }

        $tasks = [];

        foreach ($rawTasks as $task) {

            $taskUrl = sprintf('/telegram/task/%s?user_id=%s',$task['id'],$userId);
            $controllerData = $this->userRepository->getFilteredUsers((['id' => $task['controller_id']]));
            $controllerName = $controllerData[0]['fullname'];
            $formattedDate = $this->toolClass->reformatDate($task['expired_date']);
            $messages = $this->taskEventController->getMessages($task['id']);
            $task['controller_name'] = $controllerName;
            $task['formatted_date'] = $formattedDate;
            $task['messages'] = count($messages);
            $task['stage_class'] = $stageClass;
            $task['task_url'] = $taskUrl;
            $tasks[] = $task;
        }
        return $tasks;
    }
}