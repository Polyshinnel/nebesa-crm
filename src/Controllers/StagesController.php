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
    private PaymentController $paymentController;
    private WorkerController $workerController;

    public function __construct(
        DealRepository $dealRepository,
        EventRepository $eventRepository,
        OrderDetailRepository $orderDetailRepository,
        StagesRepository $stagesRepository,
        PaymentController $paymentController,
        WorkerController $workerController
    )
    {
        $this->dealRepository = $dealRepository;
        $this->eventRepository = $eventRepository;
        $this->orderDetailRepository = $orderDetailRepository;
        $this->stagesRepository = $stagesRepository;
        $this->paymentController = $paymentController;
        $this->workerController = $workerController;
    }

    public function getStagesAndDeals($funnelId)
    {
        $stages = $this->stagesRepository->getVisibleStages($funnelId);
        $formattedStagesArr = [];
        foreach ($stages as $stage) {
            $id = $stage['id'];
            $dealBlock = [];
            $deals = $this->dealRepository->getFilteredDeals($funnelId,$id);
            $dealsCount = count($deals);
            if (!empty($deals)) {
                foreach ($deals as $deal) {
                    $workerPaymentData = $this->paymentController->getWorkerDealByOrderId($deal['id']);
                    $workerName = '';
                    if($workerPaymentData) {
                        $workerInfo = $this->workerController->getWorkerById($workerPaymentData['brigade_id']);
                        $workerName = $workerInfo['name'];
                    }
                    $deal['worker_name'] = $workerName;
                    $dealsFinal[] = $deal;
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
                        'worker_name' => $workerName
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

    public function getAllDeals($funnelId) {
        $deals = $this->dealRepository->getAllDeals($funnelId);
        $processedDeals = [];
        foreach ($deals as $deal) {
            $messages = $this->eventRepository->getMessageEvent($deal['id']);
            $messageCount = count($messages);
            $deal['message_count'] = $messageCount;
            $colorClassPayment = 'success';
            $payment_status = 'Оплачено';

            $workerPaymentData = $this->paymentController->getWorkerDealByOrderId($deal['id']);
            $workerName = '';
            if($workerPaymentData) {
                $workerInfo = $this->workerController->getWorkerById($workerPaymentData['brigade_id']);
                $workerName = $workerInfo['name'];
            }

            if($deal['payed_sum'] != $deal['total_sum']) {
                $colorClassPayment = 'canceled';
                $payment_status = 'Не оплачено';
            }
            $deal['payment_status'] = $payment_status;
            $deal['color_class_payment'] = $colorClassPayment;
            $deal['worker_name'] = $workerName;
            $processedDeals[] = $deal;
        }
        return $processedDeals;
    }
}