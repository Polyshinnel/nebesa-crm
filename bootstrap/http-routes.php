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
use App\Pages\PaymentDetailActionPage;
use App\Pages\PaymentPage;
use App\Pages\ProductPaymentActionPage;
use App\Pages\SearchPage;
use App\Pages\TaskPage;
use App\Pages\Telegram\TelegramPage;
use App\Pages\WorkerActionPage;
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
        $group->get('payment-board',[PaymentPage::class,'get']);
        $group->get('add-brigade',[PaymentPage::class,'addBrigade']);
        $group->get('payment-products',[PaymentPage::class,'productsList']);
        $group->get('edit-payment-products/{id}',[PaymentPage::class,'editProducts']);
        $group->get('add-payment-products',[PaymentPage::class,'addProducts']);
        $group->get('payment-list',[PaymentPage::class,'paymentList']);
        $group->get('payment-edit/{id}',[PaymentPage::class,'EditPayment']);

        $group->get('tasks', [TaskPage::class,'get']);
        $group->get('tasks/task/{id}', [TaskPage::class,'getTask']);
        $group->post('add-task', [TaskPage::class, 'createTask']);
        $group->post('update-task-stage', [TaskPage::class, 'updateTaskStage']);
        $group->post('update-task', [TaskPage::class, 'updateTask']);
        $group->post('message-task', [TaskPage::class, 'createTaskMessage']);



        $group->post('change-stage',[ChangeStagePage::class,'get']);
        $group->post('add-deal',[AddDealPage::class,'get']);
        $group->post('add-event',[AddEventPage::class,'get']);


        $group->post('worker-deals-search',[PaymentPage::class,'searchDeals']);

        $group->post('send-sms',[CardPage::class,'sendSms']);
        $group->post('update-payment',[CardPage::class,'updateDeal']);
        $group->post('add-worker',[CardPage::class,'addWorker']);

        $group->post('create-worker',[WorkerActionPage::class,'create']);
        $group->post('update-worker',[WorkerActionPage::class,'update']);
        $group->post('delete-worker',[WorkerActionPage::class,'delete']);

        $group->post('create-products',[ProductPaymentActionPage::class,'create']);
        $group->post('update-product',[ProductPaymentActionPage::class,'update']);
        $group->post('delete-product',[ProductPaymentActionPage::class,'delete']);
        $group->post('search-product',[ProductPaymentActionPage::class,'searchProducts']);

        $group->post('create-deal-detail',[PaymentDetailActionPage::class,'create']);
        $group->post('update-deal-detail',[PaymentDetailActionPage::class,'update']);
        $group->post('delete-deal-detail',[PaymentDetailActionPage::class,'delete']);
        $group->post('money-deal-detail',[PaymentDetailActionPage::class,'addPayment']);
    })->add(BasicAuthMiddleware::class);

    $app->get('/telegram', [TelegramPage::class, 'get']);
    $app->get('/telegram/new-tasks', [TelegramPage::class, 'getNewTasks']);
    $app->get('/telegram/process-tasks', [TelegramPage::class, 'processTasks']);
    $app->get('/telegram/success-tasks', [TelegramPage::class, 'successTasks']);
    $app->get('/telegram/task/{id}', [TelegramPage::class, 'getTask']);

    $app->post('/telegram/check-user', [TelegramPage::class, 'checkUser']);
    $app->post('/telegram/auth-user', [TelegramPage::class, 'authUser']);
    $app->post('/telegram/update-task', [TelegramPage::class, 'updateTask']);
};