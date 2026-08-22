<?php

namespace App\Repositories;

use App\Db\Connect;
use PDO;

abstract class Repository
{
    protected PDO $pdo;

    public function __construct()
    {
        $this->pdo = Connect::connect();
    }

    abstract protected static function getTable(): string;
    abstract protected static function getModel(): string;

    public function create(): bool{
        if()
    }

}
