<?php


namespace App\Pages;


use App\Controllers\CalendarController;
use App\Controllers\CalendarDayController;
use App\Controllers\HeaderController;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Psr7\Factory\StreamFactory;
use Slim\Psr7\Headers;
use Slim\Psr7\Response;
use Slim\Views\Twig;

class CalendarDayPage
{
    private Twig $twig;
    private HeaderController $headerController;
    private CalendarDayController $calendarController;

    public function __construct(Twig $twig,HeaderController $headerController,CalendarDayController $calendarController)
    {
        $this->twig = $twig;
        $this->headerController = $headerController;
        $this->calendarController = $calendarController;
    }

    public function get(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface{
        $login = trim($_COOKIE["user"]);
        $headerData = $this->headerController->getHeaderData($login);
        $params = $request->getQueryParams();
        $date = $params['date'];
        $deals = $this->calendarController->getDealsByDate($date);



        $data = $this->twig->fetch('calendar-day.twig', [
            'title' => 'Сделки на '.$date,
            'userName' => $headerData['name'],
            'avatar' => $headerData['avatar'],
            'workAreaTitle' => 'Сделки на '.$date,
            'funnelSwitch' => false,
            'deals' => $deals
        ]);
        return new Response(
            200,
            new Headers(['Content-Type' => 'text/html']),
            (new StreamFactory())->createStream($data)
        );
    }


}