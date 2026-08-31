<?php

namespace App\Services;

use App\Exceptions\{
    ForbiddenException,
    RessourceNotFoundException,
    UnthorizedException,
    InvalidArgumentException
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
        if ($o instanceof User || $o instanceof Agency) {
            if (!isset($userid)) {
                throw new UnthorizedException('User Id is required');
            }
            $user = $this->userRepository->findById($userId);

            if ($user === null) {
                throw new RessourceNotFoundException();
            }


            if ($user->getIsAdmin() === false) {
                throw new ForbiddenException('Only an admin can create this type of entity');
            }
        }

        if (!$o->assertData()) {
            throw new InvalidArgumentException('Invalid arguments');
        }

        $newEntry = $this->repository->create($o);

        return $newEntry ? $newEntry : throw new RuntimeException('Entry cannot been created');
    }

    public function findAllService(?int $id = null): array
    {
        if ($this instanceof User) {
            if (!isset($id)) {
                throw new UnthorizedException('User Id is required');
            }
            $user = $this->userRepository->findById($id);

            if ($user->getIsAdmin() === false) {
                throw new ForbiddenException('Only an Admin can access to all the users list');
            }
        }


        $entities = $this->repository->findAll();

        if ($entities === []) {
            throw new RessourceNotFoundException();
        }

        return $entities;
    }

    public function findByIdService(int $id, int $userId): ?object
    {
        if ($this instanceof User) {

            $user = $this->userRepository->findById($userId);

            if ($id !== $userId && $user->getAdmin() !== true) {
                throw new ForbiddenException('Only the account owner or an admin can access');
            }
        }

        $entity = $this->repository->findById($id);

        return $entity !== null ? $entity : throw new RessourceNotFoundException();
    }

    public function updateService(object $o, int $userId): bool
    {

        $user = $this->userRepository->findById($userId);

        if ($user === null) {
            throw new RuntimeException('User not found');
        }

        if ($o instanceof User || $o instanceof Agency) {
            if ($user->getIsAdmin() === false) {
                throw new ForbiddenException('Only an admin can create this type of entity');
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

    public function deleteService(int $id, int $userId): bool
    {

        $user = $this->userRepository->findById($userId);
        $entity = $this->repository->findById($id);

        if ($user === null) {
            throw new RuntimeException('User not found');
        }

        if ($entity instanceof User || $entity instanceof Agency) {
            if ($user->getIsAdmin() === false) {
                throw new ForbiddenException('Only an admin can delete this type of entity');
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
