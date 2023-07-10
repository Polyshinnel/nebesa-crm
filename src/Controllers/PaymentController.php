<?php


namespace App\Controllers;


use App\Repository\PaymentStatusRepository;
use App\Repository\WorkerDealsRepository;
use App\Repository\WorkerRepository;
use App\Utils\ToolClass;

class PaymentController
{
    private WorkerDealsRepository $workerDealsRepository;
    private ToolClass $toolClass;
    private PaymentStatusRepository $paymentStatusRepository;
    private WorkerRepository $workerRepository;


    public function __construct(
        WorkerDealsRepository $workerDealsRepository,
        ToolClass $toolClass,
        PaymentStatusRepository $paymentStatusRepository,
        WorkerRepository $workerRepository
    )
    {
        $this->workerDealsRepository = $workerDealsRepository;
        $this->toolClass = $toolClass;
        $this->paymentStatusRepository = $paymentStatusRepository;
        $this->workerRepository = $workerRepository;
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
        if(isset($params['date_start']) and !isset($params['brigade_name']) and !isset($params['status_name'])) {

            $dateStart = $this->toolClass->reformatDate($params['date_start'], 'en');
            $dateEnd = $this->workerDealsRepository->getLastWorkerDealsData();

            if(isset($params['date_end'])){
                $dateEnd = $this->toolClass->reformatDate($params['date_end'], 'en');
            }
            return $this->workerDealsRepository->getWorkerDealsData($dateStart, $dateEnd);
        }

        //если есть дата окончания и нет других параметров
        if(isset($params['date_end']) and !isset($params['brigade_name']) and !isset($params['status_name'])) {
            $dateEnd = $this->toolClass->reformatDate($params['date_end'], 'en');
            $dateStart = $this->workerDealsRepository->getFirstWorkerDealsData();
            if(isset($params['date_start'])){
                $dateStart = $this->toolClass->reformatDate($params['date_start'], 'en');
            }
            return $this->workerDealsRepository->getWorkerDealsData($dateStart, $dateEnd);
        }

        //если есть статус или бригада
        if(isset($params['brigade_name']) or isset($params['status_name'])){
            $filter = [];

            if(isset($params['brigade_name'])) {
                $brigadeData = $this->workerRepository->getWorkerByName($params['brigade_name']);
                $filter['brigade_id'] = $brigadeData['id'];
            }

            if(isset($params['status_name'])) {
                $statusData = $this->paymentStatusRepository->getPaymentStatusByName($params['status_name']);
                $filter['status_id'] = $statusData['id'];
            }

            $dateStart = $this->workerDealsRepository->getFirstWorkerDealsData();
            $dateEnd = $this->workerDealsRepository->getLastWorkerDealsData();

            if(isset($params['date_start'])){
                $dateStart = $this->toolClass->reformatDate($params['date_start'], 'en');
            }

            if(isset($params['date_end'])){
                $dateEnd = $this->toolClass->reformatDate($params['date_end'], 'en');
            }

            return $this->workerDealsRepository->getFilteredWorkerDealsData($filter, $dateStart, $dateEnd);
        }

        return $this->workerDealsRepository->getAllWorkerDeals();
    }


}