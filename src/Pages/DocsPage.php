<?php

namespace App\Pages;

use App\Controllers\Documents\ActDocument;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Psr7\Factory\StreamFactory;
use Slim\Psr7\Headers;
use Slim\Psr7\Response;

class DocsPage
{
    private ActDocument $actDocument;
    public function __construct(ActDocument $actDocument) {
        $this->actDocument = $actDocument;
    }
    public function get(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $fileInfo = '';
        $params = $request->getQueryParams();
        if($params['deal']) {
            $deal = $params['deal'];
            $fileInfo = $this->actDocument->createAct($deal);
        }

        $headers = [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            'Content-Disposition' => 'attachment; filename='.$fileInfo['filename'],
            'Content-Transfer-Encoding' => 'binary'
        ];

        return new Response(
            200,
            new Headers($headers),
            (new StreamFactory())->createStreamFromFile($fileInfo['filepath'])
        );
    }
}