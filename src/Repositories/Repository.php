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

    abstract protected function getTable(): string;
    abstract protected function getModel(): string;

    public function create(object $o): bool
    {
        $data = $o->toArray();
        $cols = $o->getKeys();

        var_dump($data);

        $sql = sprintf(
            'INSERT INTO %s (%s) VALUES (%s)',
            $this->getTable(),
            implode(', ', $cols),
            implode(', ', array_map(fn($c) => ":$c", $cols))
        );

        $result = $this->pdo->prepare($sql)->execute($data);
        if ($result) {
            echo $this->pdo->lastInsertId();
        }

        return $result;
    }
}
