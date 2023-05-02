<?php


namespace App\Controllers;


use App\Repository\UserRepository;

class HeaderController
{
    private UserRepository $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function getHeaderData(String $login): array {
        $filter = [
            'name' => $login
        ];

        $data = $this->userRepository->getFilteredUsers($filter);
        return [
            'id' => $data[0]['id'],
            'name' => $data[0]['fullname'],
            'avatar' => $data[0]['avatar']
        ];
    }
}