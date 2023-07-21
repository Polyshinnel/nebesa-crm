<?php


namespace App\Controllers;


use App\Repository\ProductPaymentRepository;

class ProductPaymentController
{
    private ProductPaymentRepository $productPaymentRepository;

    public function __construct(ProductPaymentRepository $productPaymentRepository)
    {
        $this->productPaymentRepository = $productPaymentRepository;
    }

    public function getAllProducts(): array {
        return $this->productPaymentRepository->getAllProduct();
    }

    public function getProductById($id): array {
        return $this->productPaymentRepository->getProductById($id);
    }

    public function createProduct($params): string {
        $json = $params['json'];
        $jsonArr = json_decode($json, true);

        foreach ($jsonArr as $jsonItem) {
            $name = $jsonItem['name'];
            $categoryName = $jsonItem['category'];
            $price = $jsonItem['price'];
            $categoryData = $this->productPaymentRepository->getPaymentCategoriesByName($categoryName);

            $categoryId = $categoryData['id'];


            $createArr = [
                'name' => $name,
                'category_id' => $categoryId,
                'price' => $price
            ];

            $this->productPaymentRepository->createProduct($createArr);
        }

        return json_encode(['msg' => 'products was created']);
    }

    public function editProduct($params): string {
        $id = $params['id'];
        $name = $params['name'];
        $categoryName = $params['category'];
        $price = $params['price'];

        $categoryData = $this->productPaymentRepository->getPaymentCategoriesByName($categoryName);

        $categoryId = $categoryData['id'];

        $updateArr = [
            'name' => $name,
            'category_id' => $categoryId,
            'price' => $price
        ];

        $this->productPaymentRepository->updateProduct($id, $updateArr);
        return json_encode(['msg' => "product_id $id was updated"]);
    }

    public function deleteProduct($params): string {
        $id = $params['id'];
        $this->productPaymentRepository->deleteProduct($id);
        return json_encode(['msg' => "product_id $id was deleted"]);
    }

    public function searchProduct($name){
        $jsonArr = [
            'err' => 'none'
        ];
        $filter = [
            [
                'name','LIKE',"%$name%"
            ]
        ];
        $result = $this->productPaymentRepository->getFilteredProduct($filter);
        if(empty($result)) {
            $jsonArr['err'] = 'search has no result';
        } else {
            $jsonArr['products'] = $result;
        }

        return json_encode($jsonArr, JSON_UNESCAPED_UNICODE);
    }
}