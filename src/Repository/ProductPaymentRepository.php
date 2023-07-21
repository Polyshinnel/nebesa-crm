<?php


namespace App\Repository;


use App\Models\PaymentCategories;
use App\Models\PaymentProducts;

class ProductPaymentRepository
{
    private PaymentProducts $paymentProductModel;
    private PaymentCategories $paymentCategories;

    public function __construct(PaymentProducts $paymentProductModel, PaymentCategories $paymentCategories)
    {
        $this->paymentProductModel = $paymentProductModel;
        $this->paymentCategories = $paymentCategories;
    }

    public function getProductByName($productName): ?array {
        $res = $this->paymentProductModel::where('name', $productName)->get()->toArray();
        if($res) {
            return $res[0];
        }
        return [];
    }

    public function getAllProduct(): array {
        return $this->paymentProductModel::select(
            'payment_products.id',
            'payment_products.name',
            'payment_products.price',
            'payment_categories.name as category_name'
        )
            ->leftjoin('payment_categories','payment_categories.id','=','payment_products.category_id')
            ->get()
            ->toArray();
    }

    public function getProductById($id): array {
        return $this->paymentProductModel::select(
            'payment_products.id',
            'payment_products.name',
            'payment_products.price',
            'payment_products.category_id',
            'payment_categories.name as category_name'
        )
            ->leftjoin('payment_categories','payment_categories.id','=','payment_products.category_id')
            ->where('payment_products.id', $id)
            ->first()
            ->toArray();
    }

    public function createProduct($createArr): void {
        $this->paymentProductModel::create($createArr);
    }

    public function updateProduct($id, $updateArr): void {
        $this->paymentProductModel::where('id', $id)->update($updateArr);
    }

    public function deleteProduct($id): void {
        $this->paymentProductModel::where('id', $id)->delete();
    }

    public function getPaymentCategoriesByName($name) {
        return $this->paymentCategories::where('name', $name)->first()->toArray();
    }

    public function getAllPaymentCategories() {
        return $this->paymentCategories::all()->toArray();
    }

    public function getFilteredProduct($filter) {
        return $this->paymentProductModel::where($filter)->get()->toArray();
    }
}