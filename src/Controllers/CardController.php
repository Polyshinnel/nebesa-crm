<?php


namespace App\Controllers;


use App\Repository\DealRepository;
use App\Repository\EventRepository;
use App\Repository\OrderDetailRepository;
use App\Repository\StagesRepository;
use App\Repository\UserRepository;
use App\Utils\ToolClass;

class CardController
{
    private DealRepository $dealRepository;
    private EventRepository $eventRepository;
    private OrderDetailRepository $orderDetailRepository;
    private StagesRepository $stagesRepository;
    private ToolClass $toolClass;
    private UserRepository $userRepository;
    private MoySkladController $skladController;

    public function __construct(
        DealRepository $dealRepository,
        EventRepository $eventRepository,
        OrderDetailRepository $orderDetailRepository,
        StagesRepository $stagesRepository,
        ToolClass $toolClass,
        UserRepository $userRepository,
        MoySkladController $skladController
    )
    {
        $this->dealRepository = $dealRepository;
        $this->eventRepository = $eventRepository;
        $this->orderDetailRepository = $orderDetailRepository;
        $this->stagesRepository = $stagesRepository;
        $this->toolClass = $toolClass;
        $this->userRepository = $userRepository;
        $this->skladController = $skladController;
    }

    public function getCardInfo($id) {
        $deal = $this->dealRepository->getDealById($id);
        $dealProducts = $this->orderDetailRepository->getFilteredProducts($deal['order_id']);
        $dealStage = $this->stagesRepository->getStageById($deal['stage_id']);
        $dealEvents = $this->eventRepository->getDealEvents($id);
        $dealEventsProcessing = [];

        foreach ($dealEvents as $dealEvent) {
            $userId = $dealEvent['user_id'];
            $userData = $this->userRepository->getFilteredUsers(['id' => $userId]);
            $userName = $userData[0]['fullname'];
            $dealEvent['username'] = $userName;
            $dealEventsProcessing[] = $dealEvent;
        }
        $dealNameArr = explode(' ', $deal['name']);
        $dealNum = preg_replace('/[^0-9]/', '', $dealNameArr[1]);

        return [
            'deal_name' => $deal['name'],
            'agent' => $deal['agent'],
            'tag' => $deal['tag'],
            'stage_name' => $dealStage['name'],
            'stage_color' => $dealStage['color_class'],
            'dead_name' => $deal['dead_name'],
            'customer_name' => $deal['customer_name'],
            'customer_phone' => $deal['customer_phone'],
            'graveyard' => $deal['graveyard'],
            'graveyard_place' => $deal['graveyard_place'],
            'date_birth' => $deal['date_birth'],
            'date_dead' => $deal['date_dead'],
            'date_add' => $deal['date_add'],
            'date_delivery' => $deal['date_delivery'],
            'description' => htmlspecialchars_decode($deal['description']),
            'products' => $dealProducts,
            'events' => $dealEventsProcessing,
            'payed_sum' => $deal['payed_sum'],
            'total_sum' => $deal['total_sum'],
            'order_id' => $deal['order_id'],
            'dealNum' => $dealNum
        ];
    }

    public function getListStages($funnelId) {
        return $this->stagesRepository->getVisibleStages($funnelId);
    }

    public function updateCardPayment($idDeal) {
        $deal = $this->dealRepository->getDealById($idDeal);
        $skladId = $deal['sklad_id'];
        if(!empty($skladId)) {
            $paymentData = $this->skladController->getPaymentInfo($skladId);
            $this->dealRepository->updateDealPayment($idDeal, $paymentData['payed_sum'],$paymentData['total_sum']);
            return true;
        }
        return false;
    }

}