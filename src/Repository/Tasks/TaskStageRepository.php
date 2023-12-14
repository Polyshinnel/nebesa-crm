<?php

namespace App\Repository\Tasks;

use App\Models\Tasks\TaskStagesModel;

class TaskStageRepository
{
    private TaskStagesModel $taskStagesModel;

    public function __construct(TaskStagesModel $taskStagesModel)
    {
        $this->taskStagesModel = $taskStagesModel;
    }

    public function getVisibleStages(): ?array {
        return $this->taskStagesModel::where('visible', 1)->get()->toArray();
    }

    public function selectStages($selectArr): ?array {
        return $this->taskStagesModel::where($selectArr)->get()->toArray();
    }
}