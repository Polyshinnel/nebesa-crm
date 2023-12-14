<?php

namespace App\Repository\Tasks;

use App\Models\Tasks\TaskModel;

class TaskRepository
{
    private TaskModel $taskModel;

    public function __construct(TaskModel $taskModel)
    {
        $this->taskModel = $taskModel;
    }

    public function createTask($createArr): String {
        $taskInfo = $this->taskModel::create($createArr);
        return $taskInfo->id;
    }

    public function updateTask($updateArr, $id): void {
        $this->taskModel::where('id', $id)->update('id', $id);
    }

    public function getTasks($selectArr): ?array {
        return $this->taskModel::where($selectArr)->get()->toArray();
    }
}