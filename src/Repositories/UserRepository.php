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
}
