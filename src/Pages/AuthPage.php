<?php


namespace App\Pages;


use App\Repository\UserRepository;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Psr7\Factory\StreamFactory;
use Slim\Psr7\Headers;
use Slim\Psr7\Response;
use Psr\Http\Message\ResponseFactoryInterface;
use Slim\Views\Twig;

class AuthPage
{
    private Twig $twig;
    private UserRepository $userRepository;
    private ResponseFactoryInterface $responseFactory;

    public function __construct(Twig $twig,UserRepository $userRepository, ResponseFactoryInterface $responseFactory)
    {
        $this->twig = $twig;
        $this->userRepository = $userRepository;
        $this->responseFactory = $responseFactory;
    }

    public function get(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $params = $request->getQueryParams();
        $err = false;
        if(isset($params['err'])) {
            $err = true;
        }

        if(isset($_COOKIE["user"])) {
            return $response->withHeader('Location', '/')->withStatus(302);
        }
        $data = $this->twig->fetch('auth.twig', [
            'title' => 'Авторизация',
            'err' => $err
        ]);
        return new Response(
            200,
            new Headers(['Content-Type' => 'text/html']),
            (new StreamFactory())->createStream($data)
        );
    }


    public function authorize(ServerRequestInterface $request): ResponseInterface
    {
        $params = $request->getParsedBody();


        $login = $params['username'];
        $pass = md5($params['pass']);

        $selectRequest = [
            'name' => $login,
            'password' => $pass
        ];

        $authResult = 'Не правильный логин и/или пароль';

        $userData = $this->userRepository->getFilteredUsers($selectRequest);
        if(!empty($userData))
        {
            setcookie("user", $login, time() + 3600*8);
            $authResult = 'AuthSuccess';

            $response = $this->responseFactory->createResponse();
            return $response->withHeader('Location','/')->withStatus(302);
        }



        $response = $this->responseFactory->createResponse();
        return $response->withHeader('Location','/auth?err=true')->withStatus(302);
    }
}