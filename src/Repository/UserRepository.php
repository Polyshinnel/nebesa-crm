<?php

namespace App\Repository;

use App\Models\Users;

class UserRepository
{
    private Users $userModel;

    public function __construct(Users $userModel)
    {
        $this->userModel = $userModel;
    }

    public function getFilteredUsers($filter): ?array
    {
        return $this->userModel->where($filter)->get()->toArray();
    }

    public function getAllUsers(): ?array {
        return $this->userModel->all()->toArray();
    }

    public function updateUser($updateArr, $userId): void {
        $this->userModel->where('id', $userId)->update($updateArr);
    }
}