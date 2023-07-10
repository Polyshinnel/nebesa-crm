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

    public function getStatusList(): array {
        return $this->paymentStatusModel::all()->toArray();
    }

    public function getStatusById($id): array {
        return $this->paymentStatusModel::where('id', $id)->first()->toArray();
    }

    public function getPaymentStatusByName($name): array {
        return $this->paymentStatusModel::where('name', $name)->first()->toArray();
    }
}