<?php


namespace App\Pages;


use App\Controllers\CardController;
use App\Controllers\DealController;
use App\Controllers\HeaderController;
use App\Controllers\MoySkladController;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Psr7\Factory\StreamFactory;
use Slim\Psr7\Headers;
use Slim\Psr7\Response;
use Slim\Views\Twig;

class CardPage
{
    private Twig $twig;
    private HeaderController $headerController;
    private CardController $cardController;


    public function __construct(Twig $twig, HeaderController $headerController, CardController $cardController)
    {
        $this->twig = $twig;
        $this->headerController = $headerController;
        $this->cardController = $cardController;
    }

    public function get(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $login = trim($_COOKIE["user"]);
        $headerData = $this->headerController->getHeaderData($login);

        $dealId = $args['id'];
        $cardData = $this->cardController->getCardInfo($dealId);

        $data = $this->twig->fetch('card.twig', [
            'title' => 'Карточка',
            'userName' => $headerData['name'],
            'avatar' => $headerData['avatar'],
            'card' => $cardData
        ]);
        return new Response(
            200,
            new Headers(['Content-Type' => 'text/html']),
            (new StreamFactory())->createStream($data)
        );
    }
}