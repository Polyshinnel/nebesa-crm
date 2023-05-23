<?php


namespace App\Pages;


use App\Controllers\DealController;
use App\Controllers\HeaderController;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Psr7\Factory\StreamFactory;
use Slim\Psr7\Headers;
use Slim\Psr7\Response;

class ChangeStagePage
{
    private DealController $dealController;
    private HeaderController $headerController;

    public function __construct(DealController $dealController, HeaderController $headerController)
    {
        $this->dealController = $dealController;
        $this->headerController = $headerController;
    }

    public function get(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $params = $request->getParsedBody();
        $dealId = $params['deal_id'];
        $stageId = $params['stage_id'];
        $funnelId = $params['funnel_id'];
        $login = trim($_COOKIE["user"]);
        $headerData = $this->headerController->getHeaderData($login);
        $userId = $headerData['id'];
        $userName = $headerData['name'];


        $this->dealController->updateDeal($dealId,$stageId,$userName,$userId,$funnelId);

        $data = json_encode(['msg' => 'deal id='.$dealId.' get stage id='.$stageId]);

        return new Response(
            200,
            new Headers(['Content-Type' => 'text/html']),
            (new StreamFactory())->createStream($data)
        );
    }
}