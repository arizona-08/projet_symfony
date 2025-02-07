<?php

namespace App\Utils;

use App\Entity\User;
use App\Repository\UserRepository;

class UsersGetter{

    private UserRepository $userRepository;
    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function getClientsUsers(): array
    {
        $users = $this->userRepository->findAll();
        $clients = [];
        foreach ($users as $user) {
            if ($user->hasRole('ROLE_USER') || $user->hasRole('ROLE_VIP')) {
                $clients[] = $user;
            }
        }

        return $clients;
    }

    public function getUsersAgenciesHead(): array
    {

        $users = $this->userRepository->findAll();
        $agenciesHead = [];
        foreach ($users as $user) {
            if ($user->hasRole('ROLE_AGENCY_HEAD')) {
                $agenciesHead[] = $user;
            }
        }

        return $agenciesHead;
    }

    
}