<?php


namespace App\Pages;


use App\Controllers\CardController;
use App\Controllers\DealController;
use App\Controllers\HeaderController;
use App\Controllers\MoySkladController;
use App\Controllers\PaymentController;
use App\Controllers\SMSRU;
use App\Controllers\WorkerController;
use App\Repository\EventRepository;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Psr7\Factory\StreamFactory;
use Slim\Psr7\Headers;
use Slim\Psr7\Response;
use Slim\Views\Twig;
use stdClass;

class CardPage
{
    private Twig $twig;
    private HeaderController $headerController;
    private CardController $cardController;
    private EventRepository $eventRepository;
    private WorkerController $workerController;
    private PaymentController $paymentController;


    public function __construct(
        Twig $twig,
        HeaderController $headerController,
        CardController $cardController,
        EventRepository $eventRepository,
        WorkerController $workerController,
        PaymentController $paymentController
    )
    {
        $this->twig = $twig;
        $this->headerController = $headerController;
        $this->cardController = $cardController;
        $this->eventRepository = $eventRepository;
        $this->workerController = $workerController;
        $this->paymentController = $paymentController;
    }

    public function get(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $login = trim($_COOKIE["user"]);
        $headerData = $this->headerController->getHeaderData($login);
        $params = $request->getQueryParams();
        $funnelId = 1;
        if(isset($params['funnel_id'])){
            $funnelId = $params['funnel_id'];
        }

        $dealId = $args['id'];
        $cardData = $this->cardController->getCardInfo($dealId);
        $stagesList = $this->cardController->getListStages($funnelId);

        $phone = preg_replace('![^0-9]+!', '', $cardData['customer_phone']);
        $phone = substr($phone,1);
        $phone = '+7'.$phone;
        $customerName = $cardData['customer_name'];

        $dealNum = preg_replace('![^0-9]+!', '', $cardData['deal_name']);
        $workerList = $this->workerController->getWorkers();

        $workerPaymentData = $this->paymentController->getWorkerDealByOrderId($dealId);
        $workerName = '';
        if($workerPaymentData) {
            $workerInfo = $this->workerController->getWorkerById($workerPaymentData['brigade_id']);
            $workerName = $workerInfo['name'];
        }


        $memorialText = "Здравствуйте $customerName, 
        это Похоронный дом Небеса,Ваш памятник по заказу $dealNum готов. 
        Приглашаем Вас в ближайшее время произвести осмотр камня и подписать документы приема-передачи.";

        $data = $this->twig->fetch('card.twig', [
            'title' => 'Карточка',
            'userName' => $headerData['name'],
            'avatar' => $headerData['avatar'],
            'card' => $cardData,
            'stages' => $stagesList,
            'customer_phone' => $phone,
            'memorial_text' => $memorialText,
            'funnelId' => $funnelId,
            'worker_list' => $workerList,
            'worker_name' => $workerName
        ]);
        return new Response(
            200,
            new Headers(['Content-Type' => 'text/html']),
            (new StreamFactory())->createStream($data)
        );
    }

    public function sendSms(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $container = require __DIR__ . '/../../bootstrap/container.php';
        $smsApi = $container->get('config')['sms_api']['api_key'];
        $nameSms = $container->get('config')['sms_api']['sms_name'];
        $params = $request->getParsedBody();
        $login = trim($_COOKIE["user"]);
        $headerData = $this->headerController->getHeaderData($login);
        $userId = $headerData['id'];
        $userName = $headerData['name'];
        $dealId = $params['deal_id'];

        $customerNum = '';
        if(isset($params['customer_num'])) {
            $customerNum = $params['customer_num'];
        }
        $msgText = '';
        if(isset($params['msg_text'])) {
            $msgText = $params['msg_text'];
        }

        $dataMsg = [];

        if(!empty($customerNum) && !empty($msgText)) {
            $smsru = new SMSRU($smsApi);
            $data = new stdClass();
            $data->to = $customerNum;
            $data->text = $msgText;
            $data->from = $nameSms;
            $sms = $smsru->send_one($data);
            if($sms->status == "OK") {
                $dataMsg = [
                    'msg' => 'Сообщение отправлено успешно',
                    'err' => 'none',
                ];
                $text = 'Отправлено смс сообщение на номер: '.$customerNum.', '.date("d.m.Y H:i:s").', отправил '.$userName;

                $createArr = [
                    'type_event' => 'system',
                    'text' => $text,
                    'user_id' => $userId,
                    'deal_id' => $dealId,
                    'date_create' => date('Y-m-d H:i:s')
                ];
                $this->eventRepository->createEvent($createArr);
            } else {
                $dataMsg = [
                    'msg' => 'Сообщение не отправлено',
                    'err' => $sms->status_text
                ];
            }
        }


        $answer = json_encode($dataMsg,JSON_UNESCAPED_UNICODE);

        return new Response(
            200,
            new Headers(['Content-Type' => 'text/html']),
            (new StreamFactory())->createStream($answer)
        );
    }

    public function updateDeal(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $params = $request->getParsedBody();
        $dealId = $params['deal_id'];

        $dealUpdate = $this->cardController->updateCardPayment($dealId);
        $dataMsg = [
            'msg' => 'Сделка не обновлена',
            'err' => 'icorrect sklad id'
        ];
        if($dealUpdate) {
            $dataMsg = [
                'msg' => 'Данные обновлены',
                'err' => 'none'
            ];
        }

        $answer = json_encode($dataMsg,JSON_UNESCAPED_UNICODE);
        return new Response(
            200,
            new Headers(['Content-Type' => 'text/html']),
            (new StreamFactory())->createStream($answer)
        );
    }

    public function addWorker(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $params = $request->getParsedBody();
        $login = trim($_COOKIE["user"]);
        $headerData = $this->headerController->getHeaderData($login);
        $userId = $headerData['id'];
        $userName = $headerData['name'];

        $dealId = $params['deal_id'];
        $brigadeName = $params['brigade_name'];
        $brigadeId = $params['brigade_id'];

        $text = "На сделку назначена бригада: $brigadeName, назначил $userName";

        $workerPaymentData = $this->paymentController->getWorkerDealByOrderId($dealId);

        if($workerPaymentData) {
            $id = $workerPaymentData['id'];
            $this->paymentController->updateDeal($id, $brigadeId);
        } else {
            $cardInfo = $this->cardController->getCardInfo($dealId);

            $createArr = [
                'order_id' => $cardInfo['order_id'],
                'name' => $cardInfo['deal_name'],
                'dead_name' => $cardInfo['dead_name'],
                'agent_name' => $cardInfo['agent'],
                'tag' => $cardInfo['tag'],
                'funeral' => $cardInfo['graveyard'],
                'status_id' => 1,
                'task_done' => 0,
                'tasks_totals' => 0,
                'money_to_pay' => 0,
                'payment_money' => 0,
                'total_money' => 0,
                'brigade_id' => $brigadeId,
                'date_create' => date('Y-m-d H:i:s')
            ];

            $this->paymentController->createDeal($createArr);
        }

        $createArr = [
            'type_event' => 'system',
            'text' => $text,
            'user_id' => $userId,
            'deal_id' => $dealId,
            'date_create' => date('Y-m-d H:i:s')
        ];
        $this->eventRepository->createEvent($createArr);

        $dataMsg = [
            'msg' => 'Данные обновлены',
            'err' => 'none'
        ];

        $answer = json_encode($dataMsg,JSON_UNESCAPED_UNICODE);

        return new Response(
            200,
            new Headers(['Content-Type' => 'text/html']),
            (new StreamFactory())->createStream($answer)
        );
    }
}