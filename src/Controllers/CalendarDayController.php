<?php


namespace App\Controllers;


use App\Repository\DealRepository;
use App\Repository\EventRepository;

class CalendarDayController
{
    private DealRepository $dealRepository;
    private EventRepository $eventRepository;

    public function __construct(DealRepository $dealRepository, EventRepository $eventRepository)
    {
        $this->dealRepository = $dealRepository;
        $this->eventRepository = $eventRepository;
    }

    public function getDealsByDate($date) {
        $dateStart = $date.' 00:00:00';
        $dateEnd = $date.' 23:59:59';
        $filters = [
            [
                'deals.funnel_id' => 1,
                ['stages.visible','!=','0']
            ],
            [
                'deals.funnel_id' => 2,
                ['stages.visible','!=','0']
            ]
        ];

        $deals = [];

        foreach ($filters as $filter) {
            $searchResult = $this->dealRepository->getFilteredByDateDeals($dateStart,$dateEnd,$filter);
            if(!empty($searchResult)) {
                foreach ($searchResult as $searchItem) {
                    $deals[] = $searchItem;
                }
            }
        }



        if(!empty($deals)) {
            $processedDeals = [];
            foreach ($deals as $deal) {
                $messages = $this->eventRepository->getMessageEvent($deal['id']);
                $messageCount = count($messages);
                $deal['message_count'] = $messageCount;

                $colorClassPayment = 'success';
                $payment_status = 'Оплачено';

                if($deal['payed_sum'] != $deal['total_sum']) {
                    $colorClassPayment = 'canceled';
                    $payment_status = 'Не оплачено';
                }
                $deal['payment_status'] = $payment_status;
                $deal['color_class_payment'] = $colorClassPayment;
                $processedDeals[] = $deal;
            }
            $deals = $processedDeals;
        }

        return $deals;
    }
}