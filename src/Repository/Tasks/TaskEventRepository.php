<?php

namespace App\Repository\Tasks;

use App\Models\Tasks\TaskEventModel;

class TaskEventRepository
{
    private TaskEventModel $taskEventModel;

    public function __construct(TaskEventModel $taskEventModel) {
        $this->taskEventModel = $taskEventModel;
    }

    public function createEvent($createArr): void {
        $this->taskEventModel::create($createArr);
    }

    public function selectEvents($selectArr): ?array {
        return $this->taskEventModel::where($selectArr)->get()->toArray();
    }
}