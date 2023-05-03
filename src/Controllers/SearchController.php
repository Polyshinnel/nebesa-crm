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
                $processedDeals[] = $deal;
            }
            $deals = $processedDeals;
        }

        return $deals;
    }
}