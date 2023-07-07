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

    public function getWorkerDealsData($dateStart, $dateEnd) {
        return $this->workerDealsModel->whereBetween('date_create', [$dateStart, $dateEnd])->get()->toArray();
    }
}