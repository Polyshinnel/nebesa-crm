<?php


namespace App\Repository;


use App\Models\PaymentStatus;

class PaymentStatusRepository
{
    private PaymentStatus $paymentStatusModel;

    public function __construct(PaymentStatus $paymentStatusModel)
    {
        $this->paymentStatusModel = $paymentStatusModel;
    }

    public function getStatusById($id): array {
        return $this->paymentStatusModel::where('id', $id)->get()->toArray();
    }
}