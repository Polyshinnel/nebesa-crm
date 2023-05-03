<?php


namespace App\Repository;


use App\Models\Deals;

class DealRepository
{
    private Deals $dealModel;

    public function __construct(Deals $dealModel)
    {
        $this->dealModel = $dealModel;
    }

    public function createDeal($createArr): String {
        $dealModel = $this->dealModel::create($createArr);
        return $dealModel->id;
    }

    public function getFilteredDeals($stageId): ?array {
        return $this->dealModel::where('stage_id',$stageId)->orderBy('date_updated','DESC')->get()->toArray();
    }

    public function getAllDeals(): ?array {
        return $this->dealModel::select(
            'deals.id',
            'deals.name',
            'deals.agent',
            'deals.tag',
            'deals.dead_name',
            'deals.graveyard',
            'deals.date_create',
            'stages.name as stage_name',
            'stages.color_class'
        )
            ->leftjoin('stages','deals.stage_id','=','stages.id')
            ->orderBy('deals.id','DESC')
            ->get()
            ->toArray();
    }

    public function getDealById($id): ?array {
        return $this->dealModel::where('id',$id)->first()->toArray();
    }

    public function updateDealStage($dealId,$stageId): void {
        $updateArr = [
            'stage_id' => $stageId,
            'date_updated' => date('Y-m-d H:i:s')
        ];
        $this->dealModel::where('id',$dealId)->update($updateArr);
    }

    public function getFullFilteredDeals($filter) {
        return $this->dealModel::select(
            'deals.id',
            'deals.name',
            'deals.agent',
            'deals.tag',
            'deals.dead_name',
            'deals.customer_name',
            'deals.customer_phone',
            'deals.graveyard',
            'deals.date_create',
            'stages.name as stage_name',
            'stages.color_class'
        )
            ->leftjoin('stages','deals.stage_id','=','stages.id')
            ->orderBy('deals.id','DESC')
            ->where($filter)
            ->get()
            ->toArray();
    }
}