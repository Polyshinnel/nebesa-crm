<?php


namespace App\Middlevare;


use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class BasicAuthMiddleware implements MiddlewareInterface
{
    private ResponseFactoryInterface $responseFactory;

    public function __construct(ResponseFactoryInterface $responseFactory)
    {
        $this->responseFactory = $responseFactory;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        if(!isset($_COOKIE['user'])) {
            $response = $this->responseFactory->createResponse();
            return $response->withHeader('Location','/auth')->withStatus(302);
        }

        return $handler->handle($request);
    }
}
