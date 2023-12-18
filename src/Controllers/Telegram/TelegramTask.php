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

    public function generateMenu($userId) {
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
}