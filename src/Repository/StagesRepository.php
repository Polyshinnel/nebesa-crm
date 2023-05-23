<?php


namespace App\Repository;


use App\Models\Stages;

class StagesRepository
{
    private Stages $stagesModel;

    public function __construct(Stages $stagesModel)
    {
        $this->stagesModel = $stagesModel;
    }

    public function getVisibleStages($funnelId): ?array {
        $filter = [
            'visible' => 1,
            'funnel_id' => $funnelId
        ];
        return $this->stagesModel::where($filter)->get()->toArray();
    }

    public function getAllStages(): ?array {
        return $this->stagesModel::all()->toArray();
    }

    public function getStageById($stageId): array {
        return $this->stagesModel::where('id',$stageId)->first()->toArray();
    }

    public function getStageNameById($stageId): String {
        $data = $this->stagesModel::where('id',$stageId)->first()->toArray();
        return $data['name'];
    }

    public function getFirstStage($funnelId): array {
        $filter = [
            'funnel_id' => $funnelId,
            'color_class' => 'base'
        ];

        return $this->stagesModel::where($filter)->first()->toArray();
    }
}