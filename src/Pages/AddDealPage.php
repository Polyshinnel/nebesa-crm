<?php


namespace App\Pages;


use App\Controllers\DealController;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Psr7\Factory\StreamFactory;
use Slim\Psr7\Headers;
use Slim\Psr7\Response;

class AddDealPage
{
    private DealController $dealController;

    public function __construct(DealController $dealController)
    {
        $this->dealController = $dealController;
    }

    public function get(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $params = $request->getParsedBody();
        $dealNum = $params['deal_num'];
        $funnelId = $params['funnel_id'];

        $dataArr = $this->dealController->createDeal($dealNum,$funnelId);

        $data = json_encode($dataArr, JSON_UNESCAPED_UNICODE);

        return new Response(
            200,
            new Headers(['Content-Type' => 'application/json']),
            (new StreamFactory())->createStream($data)
        );
    }
}