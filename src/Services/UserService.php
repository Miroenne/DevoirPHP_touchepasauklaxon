<?php

namespace App\Services;

use App\Exceptions\InvalidCredentialsException;
use App\Services\Service;
use App\Repositories\UserRepository;
use App\Exceptions\RessourceNotFoundException;
use InvalidArgumentException;


class UserService extends Service
{
    private const DUMMY_HASH = '$2y$12$HcjNRmv2qkO8TWFZh1tRuuriPIAqJztWrmJnx1vWYv46y.tJYZCXK';
    public UserRepository $userRepository;

    public function __construct()
    {
        parent::__construct();
        $this->userRepository = new UserRepository();
    }

    protected function getRepository(): object
    {
        return new UserRepository;
    }

    public function login(string $email, string $plainPassword): array
    {
        $user = $this->userRepository->findByEmail($email);

        if ($user === null) {
            password_verify($plainPassword, self::DUMMY_HASH);
            throw new InvalidCredentialsException();
        }

        if (!password_verify($plainPassword, $user->getPasswordHash())) {
            throw new InvalidCredentialsException();
        }

        $csrfToken = bin2hex(random_bytes(32));

        return ['user' => $user, 'csrfToken' => $csrfToken];
    }

    public function findByEmailService(string $email): ?object
    {


        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new InvalidArgumentException('Invalid email address');
        }

        $user = $this->userRepository->findByEmail($email);

        return $user ? $user : throw new RessourceNotFoundException();
    }
}
