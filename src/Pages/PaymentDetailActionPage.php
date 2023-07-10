<?php


namespace App\Pages;

use App\Controllers\PaymentDetailsController;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Psr7\Factory\StreamFactory;
use Slim\Psr7\Headers;
use Slim\Psr7\Response;

class PaymentDetailActionPage
{
    private PaymentDetailsController $paymentDetailController;

    public function __construct(PaymentDetailsController $paymentDetailController)
    {
        $this->paymentDetailController = $paymentDetailController;
    }

    public function create(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
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

        $data = json_encode(['msg' => 'product was updated']);

        return new Response(
            200,
            new Headers(['Content-Type' => 'text/html']),
            (new StreamFactory())->createStream($data)
        );
    }

    public function delete(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $params = $request->getParsedBody();
        $json = json_decode($params['json'], true);

        $dealId = $json['deal_id'];
        $product_id = $json['product_id'];
        $this->paymentDetailController->deleteWorkerDealDetail($dealId, $product_id);

        $data = json_encode(['msg' => 'product was created']);

        return new Response(
            200,
            new Headers(['Content-Type' => 'text/html']),
            (new StreamFactory())->createStream($data)
        );
    }
}