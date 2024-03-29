<?php


namespace App\Pages;


use App\Controllers\DealController;
use App\Controllers\HeaderController;
use App\Controllers\PaymentController;
use App\Controllers\PaymentDetailsController;
use App\Controllers\PaymentStatusController;
use App\Controllers\ProductPaymentController;
use App\Controllers\WorkerController;
use App\Controllers\WorkerDealEventController;
use App\Repository\OrderDetailRepository;
use App\Repository\PaymentStatusRepository;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Psr7\Factory\StreamFactory;
use Slim\Psr7\Headers;
use Slim\Psr7\Response;
use Slim\Views\Twig;

class PaymentPage
{

    private Twig $twig;
    private HeaderController $headerController;
    private WorkerController $workerController;
    private ProductPaymentController $productPaymentController;
    private PaymentController $paymentController;
    private PaymentStatusController $paymentStatusController;
    private PaymentDetailsController $paymentDetailsController;
    private WorkerDealEventController $workerDealEventsController;


    public function __construct(
        Twig $twig,
        HeaderController $headerController,
        WorkerController $workerController,
        ProductPaymentController $productPaymentController,
        PaymentController $paymentController,
        PaymentStatusController $paymentStatusController,
        PaymentDetailsController $paymentDetailsController,
        WorkerDealEventController $workerDealEventController
    )
    {
        $this->twig = $twig;
        $this->headerController = $headerController;
        $this->workerController = $workerController;
        $this->productPaymentController = $productPaymentController;
        $this->paymentController = $paymentController;
        $this->paymentStatusController = $paymentStatusController;
        $this->paymentDetailsController = $paymentDetailsController;
        $this->workerDealEventsController = $workerDealEventController;
    }

    public function get(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $login = trim($_COOKIE["user"]);
        $headerData = $this->headerController->getHeaderData($login);


        $data = $this->twig->fetch('payment-board.twig', [
            'title' => 'Расчет зарплаты',
            'userName' => $headerData['name'],
            'avatar' => $headerData['avatar'],
            'funnelSwitch' => false,
            'workAreaTitle' => 'Расчет зарплаты',

        ]);

        return new Response(
            200,
            new Headers(['Content-Type' => 'text/html']),
            (new StreamFactory())->createStream($data)
        );
    }

    public function addBrigade(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface {
        $login = trim($_COOKIE["user"]);
        $headerData = $this->headerController->getHeaderData($login);
        $workers = $this->workerController->getWorkers();

        $data = $this->twig->fetch('payment-add-brigade.twig', [
            'title' => 'Список бригад',
            'userName' => $headerData['name'],
            'avatar' => $headerData['avatar'],
            'funnelSwitch' => false,
            'workAreaTitle' => 'Список бригад',
            'workers' => $workers
        ]);

        return new Response(
            200,
            new Headers(['Content-Type' => 'text/html']),
            (new StreamFactory())->createStream($data)
        );
    }

    public function productsList(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface {
        $login = trim($_COOKIE["user"]);
        $headerData = $this->headerController->getHeaderData($login);
        $productList = $this->productPaymentController->getAllProducts();

        $data = $this->twig->fetch('payment-products-list.twig', [
            'title' => 'Список номенклатуры',
            'userName' => $headerData['name'],
            'avatar' => $headerData['avatar'],
            'funnelSwitch' => false,
            'workAreaTitle' => 'Список номенклатуры',
            'products' => $productList
        ]);

        return new Response(
            200,
            new Headers(['Content-Type' => 'text/html']),
            (new StreamFactory())->createStream($data)
        );
    }

    public function addProducts(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface {
        $login = trim($_COOKIE["user"]);
        $headerData = $this->headerController->getHeaderData($login);

        $data = $this->twig->fetch('payment-add-products.twig', [
            'title' => 'Добавление номенклатуры',
            'userName' => $headerData['name'],
            'avatar' => $headerData['avatar'],
            'funnelSwitch' => false,
            'workAreaTitle' => 'Добавление номенклатуры',
        ]);

        return new Response(
            200,
            new Headers(['Content-Type' => 'text/html']),
            (new StreamFactory())->createStream($data)
        );
    }

    public function editProducts(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface {
        $id = $args['id'];
        $login = trim($_COOKIE["user"]);
        $headerData = $this->headerController->getHeaderData($login);
        $product_info = $this->productPaymentController->getProductById($id);

        $data = $this->twig->fetch('payment-edit-products.twig', [
            'title' => 'Редактирование номенклатуры',
            'userName' => $headerData['name'],
            'avatar' => $headerData['avatar'],
            'funnelSwitch' => false,
            'workAreaTitle' => 'Редактирование номенклатуры',
            'product_info' => $product_info
        ]);

        return new Response(
            200,
            new Headers(['Content-Type' => 'text/html']),
            (new StreamFactory())->createStream($data)
        );
    }

    public function paymentList(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface {
        $params = $request->getQueryParams();
        $login = trim($_COOKIE["user"]);
        $headerData = $this->headerController->getHeaderData($login);

        $workerPaymentList = $this->paymentController->getWorkerDeals($params);
        $finalWorkerPaymentList = [];

        if(!empty($workerPaymentList)) {
            foreach ($workerPaymentList as $item) {
                $status_id = $item['status_id'];
                $brigade_id = $item['brigade_id'];
                $status_info = $this->paymentStatusController->getDealStatusById($status_id);
                $brigade_info = $this->workerController->getWorkerById($brigade_id);
                $item['status_name'] = $status_info['name'];
                $item['status_class'] = $status_info['color_class'];
                $item['brigade_name'] = $brigade_info['name'];
                $finalWorkerPaymentList[] = $item;
            }
        }



        $brigadeList = $this->workerController->getWorkers();
        $statusList = $this->paymentStatusController->getStatusList();

        $dateStart = '';
        if(isset($params['date_start'])) {
            $dateStart = $params['date_start'];
        }

        $dateEnd = '';
        if(isset($params['date_end'])){
            $dateEnd = $params['date_end'];
        }


        $brigadeName = '';
        if(isset($params['brigade_name'])) {
            $brigadeName = $params['brigade_name'];
        }


        $statusName = '';
        if(isset($params['status_name'])) {
            $statusName = $params['status_name'];
        }



        $data = $this->twig->fetch('payment-list.twig', [
            'title' => 'Список к оплате',
            'userName' => $headerData['name'],
            'avatar' => $headerData['avatar'],
            'funnelSwitch' => false,
            'workAreaTitle' => 'Список к оплате',
            'worker_payment_list' => $finalWorkerPaymentList,
            'brigade_list' => $brigadeList,
            'status_list' => $statusList,
            'date_start' => $dateStart,
            'date_end' => $dateEnd,
            'brigade_name' => $brigadeName,
            'status_name' => $statusName
        ]);

        return new Response(
            200,
            new Headers(['Content-Type' => 'text/html']),
            (new StreamFactory())->createStream($data)
        );
    }

    public function EditPayment(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface {
        $id = $args['id'];
        $login = trim($_COOKIE["user"]);
        $headerData = $this->headerController->getHeaderData($login);

        $dealInfo = $this->paymentController->getWorkerDealById($id);

        $workerInfo = $this->workerController->getWorkerById($dealInfo['brigade_id']);

        $productList = $this->paymentDetailsController->getAllDealsProducts($id);
        $positionQuantity = 0;
        $positionTotal = 0;

        $eventList = $this->workerDealEventsController->getDealEvents($id);

        if(!empty($productList)) {
            foreach ($productList as $item) {
                if($item['state'] == 1){
                    $positionTotal += $item['total'];
                    $positionQuantity += 1;
                }

            }
        }

        $data = $this->twig->fetch('payment-brigade-edit.twig', [
            'title' => $dealInfo['name'],
            'userName' => $headerData['name'],
            'avatar' => $headerData['avatar'],
            'funnelSwitch' => false,
            'workAreaTitle' => $dealInfo['name'],
            'deal_id' => $id,
            'product_list' => $productList,
            'deal_total' => $positionTotal,
            'deal_quantity' => $positionQuantity,
            'event_list' => $eventList,
            'deal_info' => $dealInfo,
            'worker_info' => $workerInfo
        ]);


        return new Response(
            200,
            new Headers(['Content-Type' => 'text/html']),
            (new StreamFactory())->createStream($data)
        );
    }

    public function searchDeals(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface {
        $params = $request->getParsedBody();
        $deal = '';
        if(!empty($params['deal'])) {
            $deal = $params['deal'];
        }
        $data = $this->paymentController->getWorkerDealByName($deal);

        return new Response(
            200,
            new Headers(['Content-Type' => 'application/json']),
            (new StreamFactory())->createStream($data)
        );
    }

    public function editBrigadePayment(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface {


        return new Response(
            200,
            new Headers(['Content-Type' => 'text/html']),
            (new StreamFactory())->createStream($data)
        );
    }
}