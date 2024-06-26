<?php


namespace App\Controllers;


use App\Utils\ToolClass;

class MoySkladController
{
    private ToolClass $tools;

    public function __construct(ToolClass $tools)
    {
        $this->tools = $tools;
    }

    private function getToken(): String
    {
        $tokenPath = __DIR__ . '/../../config/token.json';
        if (file_exists($tokenPath)) {
            $json = file_get_contents($tokenPath);
            $jsonArr = json_decode($json, true);
            return $jsonArr['token'];
        }

        $container = require __DIR__ . '/../../bootstrap/container.php';
        $skladCredentials = $container->get('config')['sklad_credentials'];
        $login = $skladCredentials['login'];
        $pass = $skladCredentials['pass'];
        $credentials = base64_encode($login . ':' . $pass);
        $header = [
            "Authorization: Basic $credentials",
        ];
        $url = 'https://api.moysklad.ru/api/remap/1.2/security/token';
        $json = $this->tools->getPostRequest($url, $header);
        $jsonArr = json_decode($json, true);
        $token = $jsonArr['access_token'];

        $fileJson = json_encode(array('token' => $token), JSON_UNESCAPED_UNICODE);
        file_put_contents($tokenPath,$fileJson);
        return  $token;
    }

    private function getCustomerOrderInfo($orderNum) {
        $token = $this->getToken();
        $url = 'https://api.moysklad.ru/api/remap/1.2/entity/customerorder?search='.$orderNum.'&limit=1&order=created,desc';
        $header = [
            "Authorization: Bearer $token",
        ];
        $data = json_decode($this->tools->getGetRequest($url, $header),true);

        $orderId = $data['rows'][0]['id'];
        $description = '';
        if(isset($data['rows'][0]['description'])) {
            $description = $data['rows'][0]['description'];
        }
        $dateCreated = $data['rows'][0]['moment'];
        $customerData = $data['rows'][0]['agent']['meta']['href'];
        $customerArr = explode('/',$customerData);
        $customerId = array_pop($customerArr);
        $deliveryMoment = $data['rows'][0]['deliveryPlannedMoment'];
        $totalSum = $this->tools->normalizeNum($data['rows'][0]['sum']);
        $paymentSum = $this->tools->normalizeNum($data['rows'][0]['payedSum']);

        $attributes = $data['rows'][0]['attributes'];
        $dateBirth = '';
        $dateDead = '';
        $graveyard = '';
        $graveyardPlace = '';
        $agent = '';
        $deadName = '';
        $pasportNum = '';
        $pasportInfo = '';
        $customerAddr = '';

        foreach ($attributes as $attribute) {
            if(isset($attribute['name'])) {
                if($attribute['name'] == 'Умерший') {
                    $deadName = $attribute['value'];
                }

                if($attribute['name'] == 'Дата рождения') {
                    $dateBirth = $attribute['value'];
                    $dateFormatArr = explode(' ',$dateBirth);
                    $dateBirth = $this->tools->reformatDate($dateFormatArr[0]);
                }

                if($attribute['name'] == 'Дата смерти') {
                    $dateDead = $attribute['value'];
                    $dateFormatArr = explode(' ',$dateDead);
                    $dateDead = $this->tools->reformatDate($dateFormatArr[0]);
                }

                if($attribute['name'] == 'Кладбище') {
                    $graveyard = $attribute['value'];
                }

                if($attribute['name'] == '№ участка') {
                    $graveyardPlace = $attribute['value'];
                }

                if($attribute['name'] == 'Агент/Мастер') {
                    $agent = $attribute['value']['name'];
                }

                if($attribute['name'] == 'Серия и номер паспорта') {
                    $pasportNum = $attribute['value'];
                }

                if($attribute['name'] == 'Кем и когда выдан паспорт') {
                    $pasportInfo = $attribute['value'];
                }

                if($attribute['name'] == 'Адрес прописки заказчика') {
                    $customerAddr = $attribute['value'];
                }
            }

        }

        return [
            'customer_id' => $customerId,
            'order_id' => $orderId,
            'description' => $description,
            'date_created' => $dateCreated,
            'date_birth' => $dateBirth,
            'date_dead' => $dateDead,
            'graveyard' => $graveyard,
            'graveyard_place' => $graveyardPlace,
            'agent_name' => $agent,
            'dead_name' => $deadName,
            'delivery_moment' => $deliveryMoment,
            'total_sum' => $totalSum,
            'payed_sum' => $paymentSum,
            'passport_num' => $pasportNum,
            'passport_info' => $pasportInfo,
            'customer_addr' => $customerAddr
        ];
    }

    private function getCustomerOrderPositions($orderId): array {
        $token = $this->getToken();
        $url = 'https://api.moysklad.ru/api/remap/1.2/entity/customerorder/'.$orderId.'/positions';
        $header = [
            "Authorization: Bearer $token",
        ];
        $data = json_decode($this->tools->getGetRequest($url, $header), true);
        $productInfo = $data['rows'];
        $products = [];
        $position = 0;
        foreach ($productInfo as $productItem) {
            $position++;
            $quantity = $productItem['quantity'];
            $price = $productItem['price'];
            $price = ceil($price);
            $price = substr($price, 0,-2);
            $total = $price * $quantity;
            $productHref = $productItem['assortment']['meta']['href'];
            $productName = $this->getProductInfo($productHref);
            $products[] = [
                'position' => $position,
                'name' => $productName,
                'quantity' => $quantity,
                'price' => $price,
                'total' => $total
            ];
        }
        return $products;
    }

    private function getProductInfo($url): String {
        $token = $this->getToken();
        $header = [
            "Authorization: Bearer $token",
        ];
        $data = json_decode($this->tools->getGetRequest($url, $header), true);
        return $data['name'];
    }

    private function getCustomerInfo($customerId): array {
        $url = 'https://api.moysklad.ru/api/remap/1.2/entity/counterparty/'.$customerId;
        $token = $this->getToken();
        $header = [
            "Authorization: Bearer $token",
        ];
        $data = json_decode($this->tools->getGetRequest($url, $header), true);
        return [
            'customer_name' => $data['name'],
            'phone' => $data['phone']
        ];
    }

    public function getTotalOrderData($orderNum) {
        $commonOrderData = $this->getCustomerOrderInfo($orderNum);
        $customerInfo = $this->getCustomerInfo($commonOrderData['customer_id']);
        $products = $this->getCustomerOrderPositions($commonOrderData['order_id']);
        return [
            'common_data' => $commonOrderData,
            'customer_data' => $customerInfo,
            'products' => $products
        ];
    }

    public function getPaymentInfo($skladId) {
        $token = $this->getToken();
        $url = 'https://api.moysklad.ru/api/remap/1.2/entity/customerorder/'.$skladId;
        $header = [
            "Authorization: Bearer $token"
        ];
        $data = json_decode($this->tools->getGetRequest($url, $header),true);
        $totalSum = $this->tools->normalizeNum($data['sum']);
        $paymentSum = $this->tools->normalizeNum($data['payedSum']);


        return [
            'total_sum' => $totalSum,
            'payed_sum' => $paymentSum,
        ];
    }
}