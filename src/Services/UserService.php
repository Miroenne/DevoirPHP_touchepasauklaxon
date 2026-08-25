<?php

namespace App\Services;

use App\Services\Service;
use App\Repositories\UserRepository;
use App\Exceptions\RessourceNotFoundException;
use InvalidArgumentException;


class UserService extends Service
{
    public function __construct()
    {
        parent::__construct();
    }

    protected function getRepository(): object
    {
        return new UserRepository;
    }

    public function login(string $email, string $plainPassword) {}

    public function findByEmailService(string $email): ?object
    {
        $userRepository = new UserRepository();

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new InvalidArgumentException('Invalid email address');
        }

        $user = $userRepository->findByEmail($email);

        return $user ? $user : throw new RessourceNotFoundException();
    }
}
