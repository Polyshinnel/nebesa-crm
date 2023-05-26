<?php


namespace App\Controllers;


use App\Repository\DealRepository;
use App\Repository\EventRepository;

class SearchController
{
    private DealRepository $dealRepository;
    private EventRepository $eventRepository;

    public function __construct(DealRepository $dealRepository, EventRepository $eventRepository)
    {
        $this->dealRepository = $dealRepository;
        $this->eventRepository = $eventRepository;
    }

    public function getSearchResult($query) {
        $searchFields = [
            'deals.name',
            'deals.agent',
            'deals.dead_name',
            'deals.customer_name',
            'deals.customer_phone'
        ];

        $deals = [];

        foreach ($searchFields as $searchField) {
            $filter = [
                [
                    $searchField,'LIKE','%'.$query.'%'
                ]
            ];


            $searchResult = $this->dealRepository->getFullFilteredDeals($filter);
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