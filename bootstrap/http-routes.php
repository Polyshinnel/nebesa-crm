<?php


use App\Middlevare\BasicAuthMiddleware;
use App\Pages\AddDealPage;
use App\Pages\AuthPage;
use App\Pages\CardPage;
use App\Pages\ChangeStagePage;
use App\Pages\IndexPage;
use Slim\App;
use Slim\Routing\RouteCollectorProxy;

return static function (App $app): void {
    $app->get('/auth', [AuthPage::class, 'get']);
    $app->post('/authUser', [AuthPage::class, 'authorize']);

    $app->group('/',function (RouteCollectorProxy $group) {
        $group->get('',[IndexPage::class,'get']);
        $group->get('card/{id}',[CardPage::class,'get']);
        $group->post('change-stage',[ChangeStagePage::class,'get']);
        $group->post('add-deal',[AddDealPage::class,'get']);
    })->add(BasicAuthMiddleware::class);;
};