<?php

namespace App\Services;

use App\Exceptions\ForbiddenException;
use App\Exceptions\InvalidCredentialsException;
use App\Services\Service;
use App\Repositories\UserRepository;
use App\Exceptions\RessourceNotFoundException;
use App\Exceptions\UnthorizedException;
use App\Exceptions\InvalidArgumentException;


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

    public function findByEmailService(string $email, ?int $userId = null): ?object
    {
        if ($userId === null) {
            throw new UnthorizedException('Connexion requise');
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new InvalidArgumentException('Invalid email address');
        }

        $user = $this->userRepository->findById($userId);
        $searchedUser = $this->userRepository->findByEmail($email);

        if ($searchedUser->getId() !== $userId && $user->getIsAdmin() !== true) {
            throw new ForbiddenException('Only the account owner or an admin can access');
        }

        return $searchedUser ? $searchedUser : throw new RessourceNotFoundException();
    }
}
