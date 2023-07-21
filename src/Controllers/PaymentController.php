<?php


namespace App\Controllers;


use App\Repository\OrderDetailRepository;
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
    private OrderDetailRepository $orderDetailRepository;
    private PaymentDetailsController $paymentDetailController;


    public function __construct(
        WorkerDealsRepository $workerDealsRepository,
        ToolClass $toolClass,
        PaymentStatusRepository $paymentStatusRepository,
        WorkerRepository $workerRepository,
        OrderDetailRepository $orderDetailRepository,
        PaymentDetailsController $paymentDetailController
    )
    {
        $this->workerDealsRepository = $workerDealsRepository;
        $this->toolClass = $toolClass;
        $this->paymentStatusRepository = $paymentStatusRepository;
        $this->workerRepository = $workerRepository;
        $this->orderDetailRepository = $orderDetailRepository;
        $this->paymentDetailController = $paymentDetailController;
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

    public function getWorkerDealById($id) {
        $filter = [
            'id' => $id
        ];

        $res = $this->workerDealsRepository->getFilteredWorkerDeals($filter);
        if($res) {
            return $res[0];
        }
        return false;
    }

    public function createDeal($createArr) {
        $orderId = $createArr['order_id'];
        $orderProducts = $this->orderDetailRepository->getFilteredProducts($orderId);
        $paymentDealDetails = [];
        $totalPayment = 0;

        foreach ($orderProducts as $orderProduct) {
            $productName = $orderProduct['name'];
            $quantity = $orderProduct['quantity'];
            $paymentProductInfo = $this->paymentDetailController->getProductByName($productName);
            if(!empty($paymentProductInfo)){
                $categoryId = $paymentProductInfo['category_id'];
                $price = $paymentProductInfo['price'];
                $total = $price * $quantity;
                if($categoryId == 2) {
                    $total = $total * 0.4;
                }
                $totalPayment += $total;
                $paymentDealDetails[] = [
                    'product_name' => $productName,
                    'quantity' => $quantity,
                    'price' => $price,
                    'total' => $total
                ];
            }
        }

        $totalTasks = count($paymentDealDetails);
        $createArr['tasks_totals'] = $totalTasks;
        $createArr['total_money'] = $totalPayment;

        $workerDealId = $this->workerDealsRepository->createDeal($createArr);

        if(!empty($paymentDealDetails)){
            foreach ($paymentDealDetails as $paymentDealDetail) {
                $createDealDetail = [
                    'worker_deal' => $workerDealId,
                    'product_name' => $paymentDealDetail['product_name'],
                    'quantity' => $paymentDealDetail['quantity'],
                    'price' => $paymentDealDetail['price'],
                    'total' => $paymentDealDetail['total'],
                    'state' => 0,
                ];
                $this->paymentDetailController->createWorkerDealDetail($createDealDetail);
            }
        }
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

    public function getWorkerDealByName($dealName) {
        $jsonArr = [
            'err' => 'none'
        ];
        $filter = [
            [
                'worker_deals.name','LIKE',"%$dealName%"
            ]
        ];
        $result = $this->workerDealsRepository->getSearchWorkerDeals($filter);
        if(empty($result)) {
            $jsonArr['err'] = 'search has no result';
        } else {
            $jsonArr['deals'] = $result;
        }

        return json_encode($jsonArr, JSON_UNESCAPED_UNICODE);
    }
}