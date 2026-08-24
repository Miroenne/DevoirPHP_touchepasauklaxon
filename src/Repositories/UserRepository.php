<?php

namespace App\Repositories;

use App\Repositories\Repository;
use App\Models\User;

class UserRepository extends Repository
{

    protected function getTable(): string
    {
        return 'users';
    }
    protected function getModel(): string
    {
        return User::class;
    }

    public function findByEmail(string $email): ?object
    {

        $stmt = $this->pdo->prepare("SELECT * FROM {$this->getTable()} WHERE email = :email");
        $stmt->execute(['email' => $email]);

        $row = $stmt->fetch();

        return $row ? ($this->getModel()::toObject($row)) : null;
    }
}
