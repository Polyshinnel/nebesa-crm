<?php


namespace App\Repository;


use App\Models\WorkerDealDetails;

class WorkerDealDetailsRepository
{
    private WorkerDealDetails $workerDealsDetailModel;

    public function __construct(WorkerDealDetails $workerDealsDetailModel)
    {
        $this->workerDealsDetailModel = $workerDealsDetailModel;
    }

    public function getDealProducts($dealId): array {
        return $this->workerDealsDetailModel::where('worker_deal', $dealId)->get()->toArray();
    }

    public function getDealProductById($productId): array {
        return $this->workerDealsDetailModel::where('id', $productId)->get()->toArray();
    }

    public function createDealProduct($createArr): void {
        $this->workerDealsDetailModel::create($createArr);
    }

    public function updateDealProduct($productId, $updateArr): void {
        $this->workerDealsDetailModel::where('id', $productId)->update($updateArr);
    }

    public function deleteDealProduct($productId): void {
        $this->workerDealsDetailModel::where('id', $productId)->delete();
    }
}