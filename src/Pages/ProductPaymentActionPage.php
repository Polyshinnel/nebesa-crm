<?php


namespace App\Pages;

use App\Controllers\ProductPaymentController;
use App\Controllers\WorkerDealEventController;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Psr7\Factory\StreamFactory;
use Slim\Psr7\Headers;
use Slim\Psr7\Response;

class ProductPaymentActionPage
{
    private ProductPaymentController $productPaymentController;
    private WorkerDealEventController $workerDealEventController;

    public function __construct(ProductPaymentController $productPaymentController, WorkerDealEventController $workerDealEventController)
    {
        $this->productPaymentController = $productPaymentController;
        $this->workerDealEventController = $workerDealEventController;
    }

    public function create(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $login = trim($_COOKIE["user"]);
        $params = $request->getParsedBody();



        $data = $this->productPaymentController->createProduct($params);

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
        $data = $this->productPaymentController->editProduct($params);

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
        $data = $this->productPaymentController->deleteProduct($params);

        return new Response(
            200,
            new Headers(['Content-Type' => 'text/html']),
            (new StreamFactory())->createStream($data)
        );
    }
}