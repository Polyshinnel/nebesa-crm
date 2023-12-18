<?php

namespace App\Controllers\Telegram;

use App\Controllers\Tasks\TaskController;
use App\Repository\UserRepository;

class TelegramTask
{
    private UserRepository $userRepository;
    private TaskController $taskController;

    public function __construct(UserRepository $userRepository, TaskController $taskController) {
        $this->userRepository = $userRepository;
        $this->taskController = $taskController;
    }

    public function getTgUser($telegramId): ?string {
        $selectArr = [
            'telegram_id' => $telegramId
        ];
        $result = $this->userRepository->getFilteredUsers($selectArr);
        if(!empty($result)) {
            return $result[0]['id'];
        }

        return NULL;
    }

    public function authUser($login, $password, $telegramId): ?string {
        $selectArr = [
            'name' => $login,
            'password' => md5($password)
        ];

        $result = $this->userRepository->getFilteredUsers($selectArr);
        if(!empty($result)) {
            $userId = $result[0]['id'];
            $this->addTelegramData($telegramId, $userId);
            return $userId;
        }

        return NULL;
    }

    private function addTelegramData($telegramId, $userId): void {
        $updateArr = [
            'telegram_id' => $telegramId
        ];

        $this->userRepository->updateUser($updateArr, $userId);
    }

    public function generateMenu($userId): array {
        return [
            [
                'name' => 'Новые задачи',
                'href' => '/telegram/new-tasks?user_id='.$userId
            ],
            [
                'name' => 'Принятые задачи',
                'href' => '/telegram/process-tasks?user_id='.$userId
            ],
            [
                'name' => 'На проверке',
                'href' => '/telegram/success-tasks?user_id='.$userId
            ],
        ];
    }

    public function getFilteredTasks($userId, $taskStage): ?array {
        return $this->taskController->getFilteredTasks($userId, $taskStage);
    }

    public function getTask($taskId, $userId): ?array {
        $task = $this->taskController->getTask($taskId);
        $stageId = $task['stage_id'];
        $stageLink = 'new-tasks';
        $taskColor = 'bg-gray-600';
        $taskBtnText = 'Принять задачу';
        $nextStage = '2';

        if($stageId == 2) {
            $stageLink = 'process-tasks';
            $taskColor = 'bg-blue-500';
            $taskBtnText = 'На проверку';
            $nextStage = '3';
        }

        if($stageId == 3) {
            $stageLink = 'success-tasks';
            $taskColor = 'bg-green-600';
            $taskBtnText = 'Проверяем';
            $nextStage = '0';
        }

        $backUrl = sprintf('/telegram/%s?user_id=%s',$stageLink,$userId);
        $task['back_url'] = $backUrl;
        $task['task_color'] = $taskColor;
        $task['task_btn_text'] = $taskBtnText;
        $task['next_stage'] = $nextStage;
        return $task;
    }

    public function updateStage($taskId, $stageId, $userId) {
        $this->taskController->updateStageTask($taskId, $stageId, $userId);
    }
}