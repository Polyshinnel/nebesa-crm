<?php


namespace App\Controllers;


use App\Repository\PaymentStatusRepository;
use App\Repository\WorkerDealsRepository;
use App\Utils\ToolClass;

class PaymentController
{
    private WorkerDealsRepository $workerDealsRepository;
    private ToolClass $toolClass;
    private PaymentStatusRepository $paymentStatusRepository;

    public function __construct(WorkerDealsRepository $workerDealsRepository, ToolClass $toolClass, PaymentStatusRepository $paymentStatusRepository)
    {
        $this->workerDealsRepository = $workerDealsRepository;
        $this->toolClass = $toolClass;
        $this->paymentStatusRepository = $paymentStatusRepository;
    }

    public function getWorkerDealByOrderId($orderId) {
        $filter = [
            'order_id' => $orderId
        ];

        $res = $this->workerDealsRepository->getFilteredWorkerDeals($filter);
        if($res) {
            return $res[0];
        }
        return false;
    }

    public function createDeal($createArr) {
        $this->workerDealsRepository->createDeal($createArr);
    }

    public function updateDeal($workerPaymentId, $brigadeId) {
        $updateArr = [
            'brigade_id' => $brigadeId
        ];
        $this->workerDealsRepository->updateWorkerDeal($workerPaymentId, $updateArr);
    }

    public function getWorkerDeals($params) {
        //если есть дата начала и нет других параметров
        if(isset($params['date_start']) and !isset($params['brigade_id']) and !isset($params['status_id'])) {

            $dateStart = $this->toolClass->reformatDate($params['date_start']);
            $dateEnd = $this->workerDealsRepository->getLastWorkerDealsData();
            if(isset($params['date_end'])){
                $dateEnd = $params['date_end'];
            }
            return $this->workerDealsRepository->getWorkerDealsData($dateStart, $dateEnd);
        }

        //если есть дата окончания и нет других параметров
        if(isset($params['date_end']) and !isset($params['brigade_id']) and !isset($params['status_id'])) {
            $dateEnd = $this->toolClass->reformatDate($params['date_end']);
            $dateStart = $this->workerDealsRepository->getFirstWorkerDealsData();
            if(isset($params['date_start'])){
                $dateEnd = $params['date_start'];
            }
            return $this->workerDealsRepository->getWorkerDealsData($dateStart, $dateEnd);
        }

        //если есть статус или бригада
        if(isset($params['brigade_id']) or isset($params['status_id'])){
            $filter = [];

            if(isset($params['brigade_id'])) {
                $filter['brigade_id'] = $params['brigade_id'];
            }

            if(isset($params['status_id'])) {
                $filter['status_id'] = $params['status_id'];
            }

            $dateStart = $this->workerDealsRepository->getFirstWorkerDealsData();
            $dateEnd = $this->workerDealsRepository->getLastWorkerDealsData();

            if(isset($params['date_start'])){
                $dateStart = $this->toolClass->reformatDate($params['date_start']);
            }

            if(isset($params['date_end'])){
                $dateEnd = $this->toolClass->reformatDate($params['date_end']);
            }

            return $this->workerDealsRepository->getFilteredWorkerDealsData($filter, $dateStart, $dateEnd);
        }

        return $this->workerDealsRepository->getAllWorkerDeals();
    }


}