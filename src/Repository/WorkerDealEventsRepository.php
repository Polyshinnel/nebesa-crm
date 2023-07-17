<?php


namespace App\Repository;


use App\Models\WorkerDealDetailEvents;

class WorkerDealEventsRepository
{
    private WorkerDealDetailEvents $workerDealsDetailsEvents;

    public function __construct(WorkerDealDetailEvents $workerDealsDetailsEvents)
    {
        $this->workerDealsDetailsEvents = $workerDealsDetailsEvents;
    }

    public function createEvent($dealId, $textEvent): void {
        $createArr = [
            'deal_id' => $dealId,
            'event_text' => $textEvent,
            'date_create' => date('Y-m-d H:i:s')
        ];
        $this->workerDealsDetailsEvents::create($createArr);
    }

    public function getDealEvents($dealId) {
        return $this->workerDealsDetailsEvents::where('deal_id', $dealId)->get()->toArray();
    }
}