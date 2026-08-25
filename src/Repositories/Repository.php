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

        $sql = sprintf(
            'INSERT INTO %s (%s) VALUES (%s)',
            $this->getTable(),
            implode(', ', $cols),
            implode(', ', array_map(fn($c) => ":$c", $cols))
        );

        $stmt = $this->pdo->prepare($sql);
        $result = $stmt->execute($data);

        if ($result) {
            echo $this->pdo->lastInsertId();
        }

        return $result;
    }

    public function findById(int $id): ?object
    {
        $stmt = $this->pdo->prepare("SELECT * FROM {$this->getTable()} WHERE id = :id");
        $stmt->execute(['id' => $id]);

        $row = $stmt->fetch();

        return $row ? ($this->getModel()::toObject($row)) : null;
    }

    public function findAll(): array
    {
        $stmt = $this->pdo->query("SELECT * FROM {$this->getTable()}");
        $rows = $stmt->fetchAll();

        return array_map(fn($r) => ($this->getModel()::toObject($r)), $rows);
    }

    public function update(object $o): bool
    {
        $data = $o->toArray();
        $sets = implode(', ', array_map(fn($c) => "$c = :$c", $o->getKeys()));

        $stmt = $this->pdo->prepare("UPDATE {$this->getTable()} SET $sets WHERE id = :id");

        $stmt->execute([...$data, 'id' => $o->getId()]);

        return $stmt->rowCount() > 0;
    }

    public function delete(int $id): bool
    {
        $stmt = $this->pdo->prepare("DELETE FROM {$this->getTable()} WHERE id = :id");

        $stmt->execute(['id' => $id]);
        return $stmt->rowCount() > 0;
    }
}
