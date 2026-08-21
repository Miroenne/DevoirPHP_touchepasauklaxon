<?php

namespace App\Models;

require __DIR__ . ('/Model.php');

use JsonSerializable;

class Agency extends Model
{
    protected string $name;

    public function __construct(string $name, ?int $id = null)
    {
        parent::__construct($id);
        $this->name = $name;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    protected function getKeys(): array
    {
        return [
            'id',
            'name'
        ];
    }

    protected function getValues(): array
    {
        return [
            self::getId(),
            self::getName()
        ];
    }

    public static function toObject(array $data): static
    {
        return new static(
            id: $data['id'],
            name: $data['name']
        );
    }

    public function assertData(): bool
    {
        if (trim(self::getName()) === '') {
            return false;
        }
        return true;
    }
}
