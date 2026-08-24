<?php

namespace App\Repositories;

use App\Repositories\Repository;
use App\Models\Agency;

class AgencyRepository extends Repository
{

    protected function getTable(): string
    {
        return 'agencies';
    }

    protected function getModel(): string
    {
        return Agency::class;
    }

    public function findByName(string $name): array
    {

        $stmt = $this->pdo->prepare("SELECT * FROM {$this->getTable()} WHERE LOWER(name) LIKE :name");
        $stmt->execute(['name' => '%' . strtolower($name) . '%']);

        $rows = $stmt->fetchAll();

        return array_map(fn($r) => ($this->getModel()::toObject($r)), $rows);
    }
}
