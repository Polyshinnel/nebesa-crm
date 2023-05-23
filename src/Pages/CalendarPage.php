<?php


namespace App\Pages;


use App\Controllers\CalendarController;
use App\Controllers\HeaderController;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Psr7\Factory\StreamFactory;
use Slim\Psr7\Headers;
use Slim\Psr7\Response;
use Slim\Views\Twig;

class CalendarPage
{
    private Twig $twig;
    private HeaderController $headerController;
    private CalendarController $calendarController;

    public function __construct(Twig $twig,HeaderController $headerController,CalendarController $calendarController)
    {
        $this->twig = $twig;
        $this->headerController = $headerController;
        $this->calendarController = $calendarController;
    }

    public function get(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface{
        $login = trim($_COOKIE["user"]);
        $headerData = $this->headerController->getHeaderData($login);
        $params = $request->getQueryParams();

        $mouth = date('m');
        $year = date('Y');

        if(isset($params['mouth'])) {
            $mouth = $params['mouth'];
        }

        if(isset($params['year'])) {
            $year = $params['year'];
        }

        $mouthName = $this->calendarController->getMonthName($mouth);

        $calendar = $this->calendarController->getCalendar($mouth,$year);

        $dateLinks = $this->calendarController->getDateLink($mouth,$year);

        $data = $this->twig->fetch('calendar.twig', [
            'title' => 'Календарь',
            'userName' => $headerData['name'],
            'avatar' => $headerData['avatar'],
            'calendar' => $calendar,
            'mouth' => $mouthName,
            'year' => $year,
            'workAreaTitle' => 'Календарь',
            'funnelSwitch' => false,
            'dateLinks' => $dateLinks
        ]);
        return new Response(
            200,
            new Headers(['Content-Type' => 'text/html']),
            (new StreamFactory())->createStream($data)
        );
    }


}