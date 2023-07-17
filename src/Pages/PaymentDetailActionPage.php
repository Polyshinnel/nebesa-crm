<?php


namespace App\Pages;

use App\Controllers\PaymentDetailsController;
use App\Controllers\WorkerDealEventController;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Psr7\Factory\StreamFactory;
use Slim\Psr7\Headers;
use Slim\Psr7\Response;

class PaymentDetailActionPage
{
    private PaymentDetailsController $paymentDetailController;
    private WorkerDealEventController $workerDealEventController;

    public function __construct(PaymentDetailsController $paymentDetailController, WorkerDealEventController $workerDealEventController)
    {
        $this->paymentDetailController = $paymentDetailController;
        $this->workerDealEventController = $workerDealEventController;
    }

    public function create(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $login = trim($_COOKIE["user"]);
        $params = $request->getParsedBody();
        $json = json_decode($params['json'], true);

        $dealId = $json['deal_id'];
        $productName = $json['product_name'];
        $quantity = $json['quantity'];
        $price = $json['price'];
        $total = $json['total'];
        $state = $json['state'];

        $createArr = [
            'worker_deal' => $dealId,
            'product_name' => $productName,
            'quantity' => $quantity,
            'price' => $price,
            'total' => $total,
            'state' => $state
        ];

        $textEvent = "Добавлена позиция: $productName, количество: $quantity, цена $price";
        $this->workerDealEventController->createEvent($dealId,$textEvent,$login);

        $this->paymentDetailController->addNewWorkerDealDetail($dealId, $createArr);

        $data = json_encode(['msg' => 'product was created']);

        return new Response(
            200,
            new Headers(['Content-Type' => 'text/html']),
            (new StreamFactory())->createStream($data)
        );
    }

    public function update(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $login = trim($_COOKIE["user"]);
        $params = $request->getParsedBody();

        $json = json_decode($params['json'], true);

        $dealId = $json['deal_id'];
        $productName = $json['product_name'];
        $quantity = $json['quantity'];
        $price = $json['price'];
        $total = $json['total'];
        $state = $json['state'];
        $product_id = $json['product_id'];
        $updateArr = [
            'worker_deal' => $dealId,
            'product_name' => $productName,
            'quantity' => $quantity,
            'price' => $price,
            'total' => $total,
            'state' => $state
        ];
        $this->paymentDetailController->updateWorkerDealDetail($dealId, $product_id, $updateArr);

        $stateName = 'Не выполнено';
        if($state) {
            $stateName = 'Выполнено';
        }

        $textEvent = "Обновлена позиция: $productName, количество: $quantity, цена $price, итого $total, состояние ".$stateName;
        $this->workerDealEventController->createEvent($dealId,$textEvent,$login);

        $data = json_encode(['msg' => 'product was updated']);

        return new Response(
            200,
            new Headers(['Content-Type' => 'text/html']),
            (new StreamFactory())->createStream($data)
        );
    }

    public function delete(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $login = trim($_COOKIE["user"]);

        $params = $request->getParsedBody();
        $json = json_decode($params['json'], true);

        $dealId = $json['deal_id'];
        $product_id = $json['product_id'];

        $dealDetail = $this->paymentDetailController->getDealPositionById($product_id);

        $this->paymentDetailController->deleteWorkerDealDetail($dealId, $product_id);

        $textEvent = 'Удалена позиция '.$dealDetail['product_name'];
        $this->workerDealEventController->createEvent($dealId, $textEvent, $login);

        $data = json_encode(['msg' => 'product was created']);

        return new Response(
            200,
            new Headers(['Content-Type' => 'text/html']),
            (new StreamFactory())->createStream($data)
        );
    }

    public function addPayment(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $login = trim($_COOKIE["user"]);

        $params = $request->getParsedBody();
        $json = json_decode($params['json'], true);

        $dealId = $json['deal_id'];
        $paymentSum = $json['payment_sum'];

        $this->paymentDetailController->addPaymentMoney($dealId, $paymentSum);

        $textEvent = 'Добавлена выплата на '.$paymentSum.' руб.';
        $this->workerDealEventController->createEvent($dealId, $textEvent, $login);

        $data = json_encode(['msg' => 'product was created']);

        return new Response(
            200,
            new Headers(['Content-Type' => 'text/html']),
            (new StreamFactory())->createStream($data)
        );
    }
}