<?php


namespace App\Pages;

use App\Controllers\HeaderController;
use App\Controllers\StagesController;
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

    public function __construct(Twig $twig, HeaderController $headerController, StagesController $stagesController)
    {
        $this->twig = $twig;
        $this->headerController = $headerController;
        $this->stagesController = $stagesController;
    }

    public function get(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $login = trim($_COOKIE["user"]);
        $headerData = $this->headerController->getHeaderData($login);
        $stagesArr = $this->stagesController->getStagesAndDeals();
        $dealsAll = $this->stagesController->getAllDeals();


        $data = $this->twig->fetch('index.twig', [
            'title' => 'Главная страница',
            'userName' => $headerData['name'],
            'avatar' => $headerData['avatar'],
            'stages' => $stagesArr,
            'deals' => $dealsAll
        ]);
        return new Response(
            200,
            new Headers(['Content-Type' => 'text/html']),
            (new StreamFactory())->createStream($data)
        );
    }
}