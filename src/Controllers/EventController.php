<?php


namespace App\Controllers;


use App\Repository\EventRepository;
use App\Repository\UserRepository;
use App\Utils\ToolClass;

class EventController
{
    private EventRepository $eventRepository;
    private UserRepository $userRepository;
    private ToolClass $toolClass;

    public function __construct(
        EventRepository $eventRepository,
        UserRepository $userRepository,
        ToolClass $toolClass
    )
    {
        $this->userRepository = $userRepository;
        $this->eventRepository = $eventRepository;
        $this->toolClass = $toolClass;
    }

    public function addMessage($userId,$text,$dealId) {
        $createArr = [
            'type_event' => 'message',
            'text' => htmlspecialchars($text),
            'user_id' => $userId,
            'deal_id' => $dealId,
            'date_create' => date("Y-m-d H:i:s")
        ];

        $this->eventRepository->createEvent($createArr);
    }
}