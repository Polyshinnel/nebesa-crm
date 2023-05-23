<?php


use App\Middlevare\BasicAuthMiddleware;
use App\Pages\AddDealPage;
use App\Pages\AddEventPage;
use App\Pages\AuthPage;
use App\Pages\CalendarDayPage;
use App\Pages\CalendarPage;
use App\Pages\CardPage;
use App\Pages\ChangeStagePage;
use App\Pages\IndexPage;
use App\Pages\SearchPage;
use Slim\App;
use Slim\Routing\RouteCollectorProxy;

return static function (App $app): void {
    $app->get('/auth', [AuthPage::class, 'get']);
    $app->post('/authUser', [AuthPage::class, 'authorize']);

    $app->group('/',function (RouteCollectorProxy $group) {
        $group->get('',[IndexPage::class,'getDeals']);
        $group->get('deals',[IndexPage::class,'get']);
        $group->get('search',[SearchPage::class,'get']);
        $group->get('card/{id}',[CardPage::class,'get']);
        $group->get('calendar',[CalendarPage::class,'get']);
        $group->get('calendar-day',[CalendarDayPage::class,'get']);
        $group->post('change-stage',[ChangeStagePage::class,'get']);
        $group->post('add-deal',[AddDealPage::class,'get']);
        $group->post('add-event',[AddEventPage::class,'get']);
        $group->post('send-sms',[CardPage::class,'sendSms']);
    })->add(BasicAuthMiddleware::class);;
};