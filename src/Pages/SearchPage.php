<?php


namespace App\Pages;


use App\Controllers\HeaderController;
use App\Controllers\SearchController;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Psr7\Factory\StreamFactory;
use Slim\Psr7\Headers;
use Slim\Psr7\Response;
use Slim\Views\Twig;

class SearchPage
{
    private SearchController $searchController;
    private HeaderController $headerController;
    private Twig $twig;

    public function __construct(SearchController $searchController, HeaderController $headerController,Twig $twig)
    {
        $this->searchController = $searchController;
        $this->headerController = $headerController;
        $this->twig = $twig;
    }

    public function get(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $params = $request->getQueryParams();
        $query = '';
        if(isset($params['query'])) {
            $query = $params['query'];
        }


        $login = trim($_COOKIE["user"]);
        $headerData = $this->headerController->getHeaderData($login);
        $deals = $this->searchController->getSearchResult($query);

        $data = $this->twig->fetch('search.twig', [
            'title' => 'Результаты поиска',
            'userName' => $headerData['name'],
            'avatar' => $headerData['avatar'],
            'deals' => $deals,
            'query' => $query
        ]);

        return new Response(
            200,
            new Headers(['Content-Type' => 'text/html']),
            (new StreamFactory())->createStream($data)
        );
    }
}