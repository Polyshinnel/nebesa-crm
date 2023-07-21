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

    public function getSearchWorkerDeals($filter) {
        return $this->workerDealsModel::select(
            'worker_deals.id',
            'worker_deals.name',
            'worker_deals.dead_name',
            'worker_deals.agent_name',
            'worker_deals.tag',
            'worker_deals.funeral',
            'worker_deals.task_done',
            'worker_deals.tasks_totals',
            'worker_deals.money_to_pay',
            'worker_deals.payment_money',
            'worker_deals.total_money',
            'worker_deals.date_create',
            'workers.name as brigade_name',
            'payment_status.name as status_name',
            'payment_status.color_class as status_class'
        )
            ->leftjoin('workers','workers.id','=','worker_deals.brigade_id')
            ->leftjoin('payment_status','payment_status.id','=','worker_deals.status_id')
            ->where($filter)
            ->get()
            ->toArray();
    }
}