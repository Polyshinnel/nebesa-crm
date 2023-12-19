<?php

namespace App\Controllers\Telegram;

class TelegramMessages
{
    public function sendMessage($telegramId, $message, $typeMessage = 'Task', $taskId = NULL, $executorId = NULL): void{
        $container = require __DIR__ . '/../../../bootstrap/container.php';
        $telegramApi = $container->get('config')['telegram']['api_key'];
        $response = array(
            'chat_id' => $telegramId,
            'text' => $message,
            'parse_mode' => 'html'
        );

        if($typeMessage == 'Task') {
            $link = $this->generateLink($taskId, $executorId);
            $buttonArr = [
                [
                    'text' => 'Посмотреть задачу',
                    'url' => $link
                ]
            ];
            $button = json_encode($buttonArr, JSON_UNESCAPED_UNICODE);
            $response['reply_markup'] = '{"inline_keyboard":['.$button.']}';
        }

        $ch = curl_init('https://api.telegram.org/bot' . $telegramApi . '/sendMessage');
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $response);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HEADER, false);
        $result = curl_exec($ch);
        curl_close($ch);
    }

    private function generateLink($taskId, $executorId): string {
        $format = 'https://crm.nebesa.online/telegram/task/%s?user_id=%s';
        return sprintf($format, $taskId, $executorId);
    }

    public function createMessage($controllerName, $taskTitle, $taskText, $expiredDate): string {

        $format = "⚠️ ПОСТАВЛЕНА НОВАЯ ЗАДАЧА! \r\n👨‍💼 Контроллер <i>%s</i> \r\n🗓️ Срок выполенения: <i>%s</i>r\n🎯 Название задачи: <i>%s</i> \r\n📃 Описание задачи: <i>%s</i> \r\n";
        return sprintf($format, $controllerName, $expiredDate, $taskTitle, $taskText);
    }
}