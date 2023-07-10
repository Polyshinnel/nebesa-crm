<?php


namespace App\Repository;


use App\Models\WorkerDeals;

class WorkerDealsRepository
{
    private WorkerDeals $workerDealsModel;

    public function __construct(WorkerDeals $workerDealsModel)
    {
        $this->workerDealsModel = $workerDealsModel;
    }

    public function getAllWorkerDeals(): array {
        return $this->workerDealsModel::all()->toArray();
    }

    public function getFilteredWorkerDeals($filter) {
        return $this->workerDealsModel::where($filter)->get()->toArray();
    }

    public function getFilteredWorkerDealsData($filter, $dateStart, $dateEnd) {
        return $this->workerDealsModel::where($filter)
            ->whereBetween('date_create', [$dateStart, $dateEnd])
            ->get()
            ->toArray();
    }

    public function updateWorkerDeal($dealId, $updateArr) {
        $this->workerDealsModel::where('id', $dealId)->update($updateArr);
    }

    public function getWorkerDealsData($dateStart, $dateEnd) {
        return $this->workerDealsModel->whereBetween('date_create', [$dateStart, $dateEnd])->get()->toArray();
    }

    public function getFirstWorkerDealsData() {
        $dealData = $this->workerDealsModel->orderBy('date_create', 'ASC')->limit(1)->offset(0)->get()->toArray();
        if(!empty($dealData)) {
            return $dealData[0]['date_create'];
        }
        return false;
    }

    public function getLastWorkerDealsData() {
        $dealData = $this->workerDealsModel->orderBy('date_create', 'DESC')->limit(1)->offset(0)->get()->toArray();
        if(!empty($dealData)) {
            return $dealData[0]['date_create'];
        }
        return false;
    }

    public function createDeal($createArr) {
        $model = $this->workerDealsModel::create($createArr);
        return $model->id;
    }
}