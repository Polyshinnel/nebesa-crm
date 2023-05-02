<?php


namespace App\Repository;


use App\Models\OrderDetails;

class OrderDetailRepository
{
    private OrderDetails $orderDetailModel;

    public function __construct(OrderDetails $orderDetailModel)
    {
        $this->orderDetailModel = $orderDetailModel;
    }

    public function createOrderDetail($createArr): void {
        $this->orderDetailModel::create($createArr);
    }

    public function getFilteredProducts($orderId): ?array {
        return $this->orderDetailModel::where('order_id',$orderId)->get()->toArray();
    }
}