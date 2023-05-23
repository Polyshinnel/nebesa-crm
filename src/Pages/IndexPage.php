<?php


namespace App\Pages;

use App\Controllers\HeaderController;
use App\Controllers\StagesController;
use App\Repository\FunnelRepository;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Psr7\Factory\StreamFactory;
use Slim\Psr7\Headers;
use Slim\Psr7\Response;
use Slim\Views\Twig;

class IndexPage
{
    private Twig $twig;
    private HeaderController $headerController;
    private StagesController $stagesController;
    private ResponseFactoryInterface $responseFactory;
    private FunnelRepository $funnelRepository;

    public function __construct(
        Twig $twig,
        HeaderController $headerController,
        StagesController $stagesController,
        ResponseFactoryInterface $responseFactory,
        FunnelRepository $funnelRepository
    )
    {
        $this->twig = $twig;
        $this->headerController = $headerController;
        $this->stagesController = $stagesController;
        $this->responseFactory = $responseFactory;
        $this->funnelRepository = $funnelRepository;
    }

    public function get(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $params = $request->getQueryParams();
        $funnelId = 1;

        if(isset($params['funnel_id'])){
            $funnelId = $params['funnel_id'];
        }

        $funnelInfo = $this->funnelRepository->getFunnelById($funnelId);
        $funnelName = $funnelInfo['name'];
        $funnels = $this->funnelRepository->getAllFunnels();


        $login = trim($_COOKIE["user"]);
        $headerData = $this->headerController->getHeaderData($login);
        $stagesArr = $this->stagesController->getStagesAndDeals($funnelId);
        $dealsAll = $this->stagesController->getAllDeals($funnelId);
        $workAreaTitle = $funnelName;


        $data = $this->twig->fetch('index.twig', [
            'title' => 'Главная страница',
            'userName' => $headerData['name'],
            'avatar' => $headerData['avatar'],
            'stages' => $stagesArr,
            'deals' => $dealsAll,
            'funnelId' => $funnelId,
            'workAreaTitle' => $workAreaTitle,
            'funnelList' => $funnels,
            'funnelSwitch' => true
        ]);
        return new Response(
            200,
            new Headers(['Content-Type' => 'text/html']),
            (new StreamFactory())->createStream($data)
        );
    }

    public function getDeals(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $response = $this->responseFactory->createResponse();
        return $response->withHeader('Location','/deals')->withStatus(302);
    }
}