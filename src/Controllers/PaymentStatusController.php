<?php


namespace App\Controllers;


use App\Repository\PaymentStatusRepository;
use App\Repository\WorkerDealsRepository;

class PaymentStatusController
{
    private PaymentStatusRepository $paymentStatusRepository;
    private WorkerDealsRepository $workerDealsRepository;

    public function __construct(PaymentStatusRepository $paymentStatusRepository, WorkerDealsRepository $workerDealsRepository)
    {
        $this->paymentStatusRepository = $paymentStatusRepository;
        $this->workerDealsRepository = $workerDealsRepository;
    }

    public function getStatusList() {
        return $this->paymentStatusRepository->getStatusList();
    }

    public function getDealStatusById($id) {
        return $this->paymentStatusRepository->getStatusById($id);
    }
}