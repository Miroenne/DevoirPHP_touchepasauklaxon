<?php

namespace App\Repositories;

use App\Repositories\Repository;
use App\Models\Trip;
use DateTimeImmutable;
use DateTimeZone;

class TripRepository extends Repository
{

    protected function getTable(): string
    {
        return 'trips';
    }

    protected function getModel(): string
    {
        return Trip::class;
    }

    public function findActualTrips(): array
    {

        $date = new DateTimeImmutable('now', new DateTimeZone('Europe/Paris'));
        $date = $date->format('Y-m-d H:i:s');
        $stmt = $this->pdo->prepare("SELECT * FROM {$this->getTable()} WHERE 
        departureDateTime > :date AND availablePlaces > 0");
        $stmt->execute(['date' => $date]);

        $rows = $stmt->fetchAll();

        return array_map(fn($r) => ($this->getModel()::toObject($r)), $rows);
    }
}
