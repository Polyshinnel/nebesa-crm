<?php


namespace App\Pages;


use App\Controllers\DealController;
use App\Controllers\HeaderController;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Psr7\Factory\StreamFactory;
use Slim\Psr7\Headers;
use Slim\Psr7\Response;
use Slim\Views\Twig;

class PaymentPage
{

    private Twig $twig;
    private HeaderController $headerController;

    public function __construct(Twig $twig, HeaderController $headerController)
    {
        $this->twig = $twig;
        $this->headerController = $headerController;
    }

    public function get(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $login = trim($_COOKIE["user"]);
        $headerData = $this->headerController->getHeaderData($login);

        $data = $this->twig->fetch('payment-board.twig', [
            'title' => 'Расчет зарплаты',
            'userName' => $headerData['name'],
            'avatar' => $headerData['avatar'],
            'funnelSwitch' => false,
            'workAreaTitle' => 'Расчет зарплаты',
        ]);

        return new Response(
            200,
            new Headers(['Content-Type' => 'text/html']),
            (new StreamFactory())->createStream($data)
        );
    }

    public function addBrigade(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface {
        $login = trim($_COOKIE["user"]);
        $headerData = $this->headerController->getHeaderData($login);

        $data = $this->twig->fetch('payment-add-brigade.twig', [
            'title' => 'Список бригад',
            'userName' => $headerData['name'],
            'avatar' => $headerData['avatar'],
            'funnelSwitch' => false,
            'workAreaTitle' => 'Список бригад',
        ]);

        return new Response(
            200,
            new Headers(['Content-Type' => 'text/html']),
            (new StreamFactory())->createStream($data)
        );
    }

    public function productsList(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface {
        $login = trim($_COOKIE["user"]);
        $headerData = $this->headerController->getHeaderData($login);

        $data = $this->twig->fetch('payment-products-list.twig', [
            'title' => 'Список номенклатуры',
            'userName' => $headerData['name'],
            'avatar' => $headerData['avatar'],
            'funnelSwitch' => false,
            'workAreaTitle' => 'Список номенклатуры',
        ]);

        return new Response(
            200,
            new Headers(['Content-Type' => 'text/html']),
            (new StreamFactory())->createStream($data)
        );
    }

    public function addProducts(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface {
        $login = trim($_COOKIE["user"]);
        $headerData = $this->headerController->getHeaderData($login);

        $data = $this->twig->fetch('payment-add-products.twig', [
            'title' => 'Добавление номенклатуры',
            'userName' => $headerData['name'],
            'avatar' => $headerData['avatar'],
            'funnelSwitch' => false,
            'workAreaTitle' => 'Добавление номенклатуры',
        ]);

        return new Response(
            200,
            new Headers(['Content-Type' => 'text/html']),
            (new StreamFactory())->createStream($data)
        );
    }

    public function editProducts(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface {
        $id = $args['id'];
        $login = trim($_COOKIE["user"]);
        $headerData = $this->headerController->getHeaderData($login);

        $data = $this->twig->fetch('payment-edit-products.twig', [
            'title' => 'Редактирование номенклатуры',
            'userName' => $headerData['name'],
            'avatar' => $headerData['avatar'],
            'funnelSwitch' => false,
            'workAreaTitle' => 'Редактирование номенклатуры',
        ]);

        return new Response(
            200,
            new Headers(['Content-Type' => 'text/html']),
            (new StreamFactory())->createStream($data)
        );
    }

    public function paymentList(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface {
        $login = trim($_COOKIE["user"]);
        $headerData = $this->headerController->getHeaderData($login);

        $data = $this->twig->fetch('payment-list.twig', [
            'title' => 'Список к оплате',
            'userName' => $headerData['name'],
            'avatar' => $headerData['avatar'],
            'funnelSwitch' => false,
            'workAreaTitle' => 'Список к оплате',
        ]);

        return new Response(
            200,
            new Headers(['Content-Type' => 'text/html']),
            (new StreamFactory())->createStream($data)
        );
    }

    public function EditPayment(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface {
        $id = $args['id'];
        $login = trim($_COOKIE["user"]);
        $headerData = $this->headerController->getHeaderData($login);

        $data = $this->twig->fetch('payment-brigade-edit.twig', [
            'title' => 'Расчет зарплаты',
            'userName' => $headerData['name'],
            'avatar' => $headerData['avatar'],
            'funnelSwitch' => false,
            'workAreaTitle' => 'Расчет зарплаты',
        ]);

        return new Response(
            200,
            new Headers(['Content-Type' => 'text/html']),
            (new StreamFactory())->createStream($data)
        );
    }

    public function brigadeToOrder(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface {


        return new Response(
            200,
            new Headers(['Content-Type' => 'text/html']),
            (new StreamFactory())->createStream($data)
        );
    }

    public function editBrigadePayment(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface {


        return new Response(
            200,
            new Headers(['Content-Type' => 'text/html']),
            (new StreamFactory())->createStream($data)
        );
    }
}