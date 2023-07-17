<?php


namespace App\Controllers;


use App\Repository\WorkerDealEventsRepository;

class WorkerDealEventController
{
    private WorkerDealEventsRepository $workerDealsEventsRepository;
    private HeaderController $headerController;

    public function __construct(WorkerDealEventsRepository $workerDealsEventsRepository, HeaderController $headerController)
    {
        $this->workerDealsEventsRepository = $workerDealsEventsRepository;
        $this->headerController = $headerController;
    }

    public function createEvent($dealId, $eventText, $login): void {
        $userData = $this->headerController->getHeaderData($login);
        $eventText = $eventText.', сотрудник '.$userData['name'];
        $this->workerDealsEventsRepository->createEvent($dealId, $eventText);
    }

    public function getDealEvents($dealId): array {
        return $this->workerDealsEventsRepository->getDealEvents($dealId);
    }
}