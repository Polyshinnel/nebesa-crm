<?php


namespace App\Controllers;


use App\Repository\DealRepository;
use App\Repository\EventRepository;
use App\Repository\OrderDetailRepository;
use App\Repository\OrderRepository;
use App\Repository\StagesRepository;
use App\Utils\ToolClass;

class DealController
{
    private DealRepository $dealRepository;
    private OrderRepository $orderRepository;
    private OrderDetailRepository $orderDetailRepository;
    private EventRepository $eventRepository;
    private MoySkladController $skladController;
    private ToolClass $toolClass;
    private StagesRepository $stageRepository;

    public function __construct(
        DealRepository $dealRepository,
        OrderRepository $orderRepository,
        OrderDetailRepository $orderDetailRepository,
        EventRepository $eventRepository,
        MoySkladController $skladController,
        ToolClass $toolClass,
        StagesRepository $stageRepository
    )
    {
        $this->dealRepository = $dealRepository;
        $this->orderRepository = $orderRepository;
        $this->orderDetailRepository = $orderDetailRepository;
        $this->eventRepository = $eventRepository;
        $this->skladController = $skladController;
        $this->toolClass = $toolClass;
        $this->stageRepository = $stageRepository;
    }

    public function createDeal($deanNum): string
    {
        $data = $this->skladController->getTotalOrderData($deanNum);

        $createOrderArr = [
            'name' => $deanNum,
            'date_create' => date("Y-m-d")
        ];

        $orderId = $this->orderRepository->createOrder($createOrderArr);

        $products = $data['products'];
        foreach ($products as $product) {
            $createArr = [
                'order_id' => $orderId,
                'name' => $product['name'],
                'position' => $product['position'],
                'quantity' => $product['quantity'],
                'price' => $product['price']
            ];

            $this->orderDetailRepository->createOrderDetail($createArr);
        }

        $dealCreateArr = [
            'name' => 'Заказ №' . $deanNum,
            'agent' => $data['common_data']['agent_name'],
            'tag' => 'Памятники',
            'dead_name' => $data['common_data']['dead_name'],
            'customer_name' => $data['customer_data']['customer_name'],
            'customer_phone' => $data['customer_data']['phone'],
            'graveyard' => $data['common_data']['graveyard'],
            'graveyard_place' => $data['common_data']['graveyard_place'],
            'description' => htmlspecialchars($data['common_data']['description']),
            'order_id' => $orderId,
            'date_birth' => $this->toolClass->reformatDate($data['common_data']['date_birth'], 'eng'),
            'date_dead' => $this->toolClass->reformatDate($data['common_data']['date_dead'], 'eng'),
            'stage_id' => 1,
            'date_add' => date("Y-m-d H:i:s"),
            'date_create' => $data['common_data']['date_created'],
            'date_updated' => date("Y-m-d H:i:s"),
        ];


        return $this->dealRepository->createDeal($dealCreateArr);
    }

    public function updateDeal($dealId,$stageId,$userName,$userId) {
        $this->dealRepository->updateDealStage($dealId,$stageId);
        $stageName = $this->stageRepository->getStageNameById($stageId);
        $text = 'Новый этап: '.$stageName.' '.date("d.m.Y H:i:s").' переместил '.$userName;

        $createArr = [
            'type_event' => 'system',
            'text' => $text,
            'user_id' => $userId,
            'deal_id' => $dealId,
            'date_create' => date('Y-m-d H:i:s')
        ];
        $this->eventRepository->createEvent($createArr);
    }
}