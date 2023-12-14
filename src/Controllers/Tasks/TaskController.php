<?php

namespace App\Controllers\Tasks;

use App\Repository\Tasks\TaskEventRepository;
use App\Repository\Tasks\TaskRepository;
use App\Repository\Tasks\TaskStageRepository;
use App\Repository\UserRepository;

class TaskController
{
    private TaskRepository $taskRepository;
    private TaskEventRepository $eventRepository;
    private TaskStageRepository $taskStageRepository;
    private UserRepository $userRepository;
    private TaskEventController $taskEventController;

    public function __construct(
        TaskRepository $taskRepository,
        TaskEventRepository $eventRepository,
        TaskStageRepository $taskStageRepository,
        UserRepository  $userRepository,
        TaskEventController $taskEventController
    )
    {
        $this->taskRepository = $taskRepository;
        $this->eventRepository = $eventRepository;
        $this->taskStageRepository = $taskStageRepository;
        $this->userRepository = $userRepository;
        $this->taskEventController = $taskEventController;
    }

    public function createTask($executorId, $controllerId, $taskTitle, $taskText, $expiredDate) {
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

        $this->taskEventController->createEvent($taskId, 'message', $taskText, $controllerId);
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
            $stageInfo = $this->taskStageRepository->selectStages(['stage_id' => $taskData['stage_id']]);
            $stages = $this->taskStageRepository->getVisibleStages();

            $executorData = $this->userRepository->getFilteredUsers(['id' => $taskData['executor_id']]);
            $controllerData = $this->userRepository->getFilteredUsers(['id' => $taskData['controller_id']]);


            return [
                'task_name' => $taskData['task_title'],
                'stage_name' => $stageInfo['name'],
                'stage_color' => $stageInfo['color_class'],
                'expired_date' => $stageInfo['expired_date'],
                'executor_name' => $executorData['name'],
                'controller_name' => $controllerData['name'],
                'events' => $events,
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
}