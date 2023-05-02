<?php


namespace App\Controllers;


use App\Repository\DealRepository;
use App\Repository\EventRepository;
use App\Repository\OrderDetailRepository;
use App\Repository\StagesRepository;

class StagesController
{
    private DealRepository $dealRepository;
    private EventRepository $eventRepository;
    private OrderDetailRepository $orderDetailRepository;
    private StagesRepository $stagesRepository;

    public function __construct(
        DealRepository $dealRepository,
        EventRepository $eventRepository,
        OrderDetailRepository $orderDetailRepository,
        StagesRepository $stagesRepository
    )
    {
        $this->dealRepository = $dealRepository;
        $this->eventRepository = $eventRepository;
        $this->orderDetailRepository = $orderDetailRepository;
        $this->stagesRepository = $stagesRepository;
    }

    public function getStagesAndDeals()
    {
        $stages = $this->stagesRepository->getVisibleStages();
        $formattedStagesArr = [];
        foreach ($stages as $stage) {
            $id = $stage['id'];
            $dealBlock = [];
            $deals = $this->dealRepository->getFilteredDeals($id);
            $dealsCount = count($deals);
            if (!empty($deals)) {
                foreach ($deals as $deal) {
                    $messages = $this->eventRepository->getMessageEvent($deal['id']);
                    $countMessages = count($messages);
                    $dealBlock[] = [
                        'id' => $deal['id'],
                        'name' => $deal['name'],
                        'dead_name' => $deal['dead_name'],
                        'agent' => $deal['agent'],
                        'tag' => $deal['tag'],
                        'graveyard' => $deal['graveyard'],
                        'messages_count' => $countMessages,
                        'date_create' => $deal['date_create'],
                    ];
                }
            }
            $formattedStagesArr[] = [
                'stage_id' => $id,
                'stage_name' => $stage['name'],
                'color_class' => $stage['color_class'],
                'deals_count' => $dealsCount,
                'deals' => $dealBlock
            ];
        }

        return $formattedStagesArr;
    }

    public function getAllDeals() {
        $deals = $this->dealRepository->getAllDeals();
        $processedDeals = [];
        foreach ($deals as $deal) {
            $messages = $this->eventRepository->getMessageEvent($deal['id']);
            $messageCount = count($messages);
            $deal['message_count'] = $messageCount;
            $processedDeals[] = $deal;
        }
        return $processedDeals;
    }
}