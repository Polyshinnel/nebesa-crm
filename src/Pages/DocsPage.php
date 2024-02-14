<?php

namespace App\Pages;

use App\Controllers\Documents\DocumentController;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Psr7\Factory\StreamFactory;
use Slim\Psr7\Headers;
use Slim\Psr7\Response;

class DocsPage
{
    private DocumentController $documentController;
    public function __construct(DocumentController $documentController) {
        $this->documentController = $documentController;
    }
    public function get(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $fileInfo = '';
        $params = $request->getQueryParams();
        if($params['deal']) {
            $deal = $params['deal'];
            $fileInfo = $this->documentController->createAct($deal);
        }

        return $response->withHeader('Location', $fileInfo['output'])->withStatus(302);
    }

    public function getDealDoc(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $params = $request->getQueryParams();
        $fileInfo = '';
        if($params['deal']) {
            $deal = $params['deal'];
            $fileInfo = $this->documentController->createDealDoc($deal);
        }

        return $response->withHeader('Location', $fileInfo['output'])->withStatus(302);
    }
}