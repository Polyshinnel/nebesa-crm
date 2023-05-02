<?php


namespace App\Repository;


use App\Models\Events;

class EventRepository
{
    private Events $eventModel;

    public function __construct(Events $eventModel)
    {
        $this->eventModel = $eventModel;
    }

    public function createEvent($createArr): void {
        $this->eventModel::create($createArr);
    }

    public function getMessageEvent($dealId): ?array {
        $filter = [
            'deal_id' => $dealId,
            'type_event' => 'message'
        ];
        return $this->eventModel->where($filter)->get()->toArray();
    }

    public function getDealEvents($dealId) {
        return $this->eventModel->where('deal_id',$dealId)->get()->toArray();
    }
}