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

    public function getFilteredUsers($filter)
    {
        return $this->userModel->where($filter)->get()->toArray();
    }
}