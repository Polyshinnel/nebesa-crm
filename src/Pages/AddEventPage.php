<?php


namespace App\Pages;


use App\Controllers\DealController;
use App\Controllers\EventController;
use App\Controllers\HeaderController;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Psr7\Factory\StreamFactory;
use Slim\Psr7\Headers;
use Slim\Psr7\Response;

class AddEventPage
{
    private EventController $eventController;
    private HeaderController $headerController;

    public function __construct(EventController $eventController, HeaderController $headerController)
    {
        $this->eventController = $eventController;
        $this->headerController = $headerController;
    }

    public function get(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $params = $request->getParsedBody();
        $dealId = $params['deal_id'];
        $text = $params['text_data'];
        $type = $params['type'];

        $login = trim($_COOKIE["user"]);
        $headerData = $this->headerController->getHeaderData($login);
        $userId = $headerData['id'];



        $answer = '';
        if($type == 'message') {
            $answer = 'message_add';
            $this->eventController->addMessage($userId,$text,$dealId);
        }
        $data = [
            'msg' => $answer,
            'username' => $headerData['name'],
            'date' => date("Y-m-d H:i:s")
        ];

        $data = json_encode($data,JSON_UNESCAPED_UNICODE);

        return new Response(
            200,
            new Headers(['Content-Type' => 'text/html']),
            (new StreamFactory())->createStream($data)
        );
    }
}