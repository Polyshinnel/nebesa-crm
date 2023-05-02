<?php


namespace App\Repository;


use App\Models\Orders;

class OrderRepository
{
    private Orders $orderModel;

    public function __construct(Orders $orderModel)
    {
        $this->orderModel = $orderModel;
    }

    public function createOrder($createArr): String {
        $model = $this->orderModel::create($createArr);
        return $model->id;
    }
}