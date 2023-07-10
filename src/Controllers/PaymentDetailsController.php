<?php


namespace App\Controllers;


use App\Repository\ProductPaymentRepository;
use App\Repository\WorkerDealDetailsRepository;
use App\Repository\WorkerDealsRepository;

class PaymentDetailsController
{
    private WorkerDealDetailsRepository $workerDealDetails;
    private WorkerDealsRepository $workerDeals;
    private ProductPaymentRepository $productPaymentRepository;

    public function __construct(
        WorkerDealDetailsRepository $workerDealDetails,
        WorkerDealsRepository $workerDeals,
        ProductPaymentRepository $productPaymentRepository
    )
    {
        $this->workerDeals = $workerDeals;
        $this->workerDealDetails = $workerDealDetails;
        $this->productPaymentRepository = $productPaymentRepository;
    }

    public function getProductByName($productName) {
        return $this->productPaymentRepository->getProductByName($productName);
    }

    public function createWorkerDealDetail($createArr) {
        $this->workerDealDetails->createDealProduct($createArr);
    }

    public function addNewWorkerDealDetail($dealId, $createArr) {
        $this->workerDealDetails->createDealProduct($createArr);
        $dealDetails = $this->workerDealDetails->getDealProducts($dealId);
        $this->updateCurrDeal($dealId, $dealDetails);
    }

    public function updateWorkerDealDetail($dealId, $productId, $updateArr) {
        $this->workerDealDetails->updateDealProduct($productId, $updateArr);
        $dealDetails = $this->workerDealDetails->getDealProducts($dealId);
        $this->updateCurrDeal($dealId, $dealDetails);
    }

    public function deleteWorkerDealDetail($dealId, $productId) {
        $this->workerDealDetails->deleteDealProduct($productId);
        $dealDetails = $this->workerDealDetails->getDealProducts($dealId);
        $this->updateCurrDeal($dealId, $dealDetails);
    }

    public function getAllDealsProducts($dealId) {
        return $this->workerDealDetails->getDealProducts($dealId);
    }

    private function updateCurrDeal($dealId, $dealDetails) {
        $tasksTotal = 0;
        $tasksDone = 0;
        $moneyToPay = 0;
        $totalMoney = 0;

        if(!empty($dealDetails)) {
            foreach ($dealDetails as $dealDetail) {
                $tasksTotal += 1;
                $totalMoney += $dealDetail['total'];
                if($dealDetail['state'] == 1) {
                    $tasksDone += 1;
                    $moneyToPay += $dealDetail['total'];
                }
            }
        }

        $updateArr = [
            'task_done' => $tasksDone,
            'tasks_totals' => $tasksTotal,
            'money_to_pay' => $moneyToPay,
            'total_money' => $totalMoney,
            'status_id' => 1
        ];

        if($tasksDone > 0) {
            $updateArr['status_id'] = 2;
        }

        if(($tasksDone == $tasksTotal) && ($tasksTotal > 0)) {
            $updateArr['status_id'] = 3;
        }

        $this->workerDeals->updateWorkerDeal($dealId, $updateArr);
    }
}