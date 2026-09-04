<?php

namespace App\Services;

use App\Exceptions\{
    ForbiddenException,
    RessourceNotFoundException,
    UnthorizedException,
    InvalidArgumentException,
    InvalidCredentialsException
};
use App\Repositories\Repository;
use App\Repositories\UserRepository;
use RuntimeException;
use App\Models\{Agency, Trip, User};

abstract class Service
{

    protected Repository $repository;
    protected UserRepository $userRepository;

    public function __construct()
    {
        $this->repository = $this->getRepository();
        $this->userRepository = new UserRepository();
    }

    abstract protected function getRepository(): object;

    public function createService(object $o, ?int $userId = null): bool
    {
        if ($userId === null || trim($userId) === '') {
            throw new InvalidCredentialsException('Connection required');
        }

        if ($o instanceof User || $o instanceof Agency) {
            if (!isset($userId)) {
                throw new UnthorizedException('Connection required');
            }
            $user = $this->userRepository->findById($userId);

            if ($user === null) {
                throw new RessourceNotFoundException();
            }


            if ($user->getIsAdmin() === false) {
                if ($o instanceof User) {
                    throw new ForbiddenException('Only an admin can create a user');
                }
                if ($o instanceof Agency) {
                    throw new ForbiddenException('Only an admin can create an agency');
                }
            }
        }

        if (!$o->assertData()) {
            throw new InvalidArgumentException('Invalid arguments');
        }

        $newEntry = $this->repository->create($o);
        $errorMessage = 'There was an error on the server and the request could not be completed ';
        return $newEntry ? $newEntry : throw new RuntimeException($errorMessage);
    }

    public function findAllService(?int $id = null): array
    {

        if ($this instanceof UserService) {
            if ($id === null) {
                throw new UnthorizedException('Authentification required');
            }
            $user = $this->userRepository->findById($id);

            if ($user->getIsAdmin() === false) {
                throw new ForbiddenException('Only an Admin can access to the users list');
            }
        }


        $entities = $this->repository->findAll();

        if ($entities === []) {
            throw new RessourceNotFoundException();
        }

        return $entities;
    }

    public function findByIdService(int $id, ?int $userId = null): ?object
    {

        if (!isset($userId)) {
            throw new UnthorizedException('Authentification required');
        }

        if ($this instanceof UserService) {

            $user = $this->userRepository->findById($userId);

            if ($id !== $userId && $user->getIsAdmin() !== true) {
                throw new ForbiddenException('Only the account owner or an admin can access');
            }
        }


        $entity = $this->repository->findById($id);

        return $entity !== null ? $entity : throw new RessourceNotFoundException();
    }

    public function updateService(object $o, ?int $userId): bool
    {
        if (!isset($userId)) {
            throw new UnthorizedException('Connection required');
        }
        $user = $this->userRepository->findById($userId);

        if ($user === null) {
            throw new RuntimeException('User not found');
        }

        if ($user->getIsAdmin() === false) {
            if ($o instanceof User) {
                throw new ForbiddenException('Only an admin can update a user');
            }
            if ($o instanceof Agency) {
                throw new ForbiddenException('Only an admin can update an agency');
            }
        }

        if ($o instanceof Trip) {
            if ($user->getId() !== $o->getAuthorId() && $user->getIsAdmin() === false) {
                throw new ForbiddenException('Only the creator of this trip or an admin can update it');
            }
        }

        if (!$o->assertData()) {
            throw new InvalidArgumentException('Invalid arguments');
        }

        $entity = $this->repository->update($o);

        return $entity ? $entity : throw new RessourceNotFoundException();
    }

    public function deleteService(int $id, ?int $userId): bool
    {
        if (!isset($userId)) {
            throw new UnthorizedException('Connection required');
        }

        $user = $this->userRepository->findById($userId);
        $entity = $this->repository->findById($id);

        if ($user === null) {
            throw new RessourceNotFoundException();
        }

        if ($user->getIsAdmin() === false) {
            if ($this instanceof UserService) {
                throw new ForbiddenException('Only an admin can delete a user');
            }
            if ($this instanceof AgencyService) {
                throw new ForbiddenException('Only an admin can delete an agency');
            }
        }



        if ($entity instanceof Trip && $user->getId() !== $entity->getAuthorId()) {
            if ($user->getIsAdmin() === false) {
                throw new ForbiddenException('Only the author of this trip or an admin can delete it');
            }
        }

        $entity = $this->repository->delete($id);

        return $entity ? $entity : throw new RessourceNotFoundException();
    }
}
