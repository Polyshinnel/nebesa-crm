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

        $headers = [
            'Content-Description' => 'File Transfer',
            'Content-Type' => 'application/octet-stream',
            'Content-Disposition' => 'attachment; filename='.$fileInfo['filename'],
            'Content-Transfer-Encoding' => 'binary',
            'Expires' => '0',
            'Content-Length' => filesize($fileInfo['filepath']),
        ];

        return new Response(
            200,
            new Headers($headers),
            (new StreamFactory())->createStreamFromFile($fileInfo['filepath'])
        );
    }

    public function getDelalDoc(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {

    }
}