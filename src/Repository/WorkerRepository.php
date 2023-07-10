<?php


namespace App\Repository;


use App\Models\Workers;

class WorkerRepository
{
    private Workers $workerModel;

    public function __construct(Workers $workerModel)
    {
        $this->workerModel = $workerModel;
    }

    public function getWorkers(): array {
        return $this->workerModel::all()->toArray();
    }

    public function createWorkers($createArr): void {
        $this->workerModel::create($createArr);
    }

    public function getWorkerById($id): array {
        return $this->workerModel::where('id', $id)->first()->toArray();
    }

    public function updateWorker($id, $updateArr): void {
        $this->workerModel::where('id', $id)->update($updateArr);
    }

    public function deleteWorker($id): void {
        $this->workerModel::where('id', $id)->delete();
    }

    public function getWorkerByName($name): array {
        return $this->workerModel::where('name', $name)->first()->toArray();
    }
}