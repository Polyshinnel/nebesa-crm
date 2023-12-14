<?php

namespace App\Controllers\Tasks;

use App\Repository\Tasks\TaskEventRepository;
use App\Repository\Tasks\TaskStageRepository;
use App\Repository\UserRepository;

class TaskEventController
{
    private UserRepository $userRepository;
    private TaskEventRepository $taskEventRepository;
    private TaskStageRepository $taskStageRepository;

    public function __construct(
        UserRepository $userRepository,
        TaskEventRepository  $taskEventRepository,
        TaskStageRepository $taskStageRepository
    ) {
        $this->userRepository = $userRepository;
        $this->taskEventRepository = $taskEventRepository;
        $this->taskStageRepository = $taskStageRepository;
    }

    public function textCollector(string $type,array $params): ?string {
        if($type == 'change stage') {
            $userId = $params['user_id'];
            $stageId = $params['stage_id'];
            $userData = $this->userRepository->getFilteredUsers(['id' => $userId]);
            $userName = $userData[0]['name'];
            $stageInfo = $this->taskStageRepository->selectStages(['id' => $stageId]);
            $stageName = $stageInfo[0]['name'];

            $format = '%s переместил задачу на этап %s, %s';
            return sprintf($format, $userName, $stageName, date('d.m.Y H:i:s'));
        }

        if($type == 'update task') {
            $userId = $params['user_id'];
            $executorId = $params['executor_id'];
            $expiredDate = $params['expired_date'];

            $userData = $this->userRepository->getFilteredUsers(['id' => $userId]);
            $userName = $userData[0]['name'];

            $executorDate = $this->userRepository->getFilteredUsers(['id' => $executorId]);
            $executorName = $executorDate[0]['name'];

            $format = '%s изменил пользователя на %s и время выполнения на %s';
            return sprintf($format, $userName, $executorName, $expiredDate);
        }

        if($type == 'create task') {
            $userId = $params['user_id'];
            $executorId = $params['executor_id'];

            $userData = $this->userRepository->getFilteredUsers(['id' => $userId]);
            $userName = $userData[0]['name'];

            $executorDate = $this->userRepository->getFilteredUsers(['id' => $executorId]);
            $executorName = $executorDate[0]['name'];

            $format = 'Создана новая задача, исполнитель: %s, контролер: %s, дата создания: %s';
            return sprintf($format, $executorName, $userName, date('d.m.Y H:i:s'));
        }

        return null;
    }

    public function createEvent($taskId, $typeEvent, $text, $userId): void {
        $createArr = [
            'task_id' => $taskId,
            'type_event' => $typeEvent,
            'event_text' => $text,
            'user_id' => $userId,
            'date_create' => date('Y-m-d H:i:s')
        ];

        $this->taskEventRepository->createEvent($createArr);
    }

    public function getEvents($taskId): ?array {
        $selectArr = [
            'task_id' => $taskId
        ];

        return $this->taskEventRepository->selectEvents($selectArr);
    }

    public function getMessages($taskId): ?array {
        $selectArr = [
            'task_id' => $taskId,
            'type_event' => 'message'
        ];

        return $this->taskEventRepository->selectEvents($selectArr);
    }
}