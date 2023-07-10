<?php


namespace App\Controllers;


use App\Repository\WorkerRepository;

class WorkerController
{
    private WorkerRepository $workerRepository;

    public function __construct(
        WorkerRepository $workerRepository
    )
    {
        $this->workerRepository=$workerRepository;
    }

    public function getWorkers(): array {
        return $this->workerRepository->getWorkers();
    }

    public function getWorkerById($workerId): array {
        return $this->workerRepository->getWorkerById($workerId);
    }

    public function addWorker($params): string {
        $name = $params['name'];
        $createArr = [
            'name' => $name
        ];
        $this->workerRepository->createWorkers($createArr);
        return json_encode(['msg' => 'worker was created']);
    }

    public function updateWorker($params): string {
        $id = $params['id'];
        $name = $params['name'];
        $updateArr = [
            'name' => $name
        ];

        $this->workerRepository->updateWorker($id, $updateArr);
        return json_encode(['msg' => "worker_id $id was updated"]);
    }

    public function deleteWorker($params): string {
        $id = $params['id'];

        $this->workerRepository->deleteWorker($id);
        return json_encode(['msg' => "worker_id $id was deleted"]);
    }


}