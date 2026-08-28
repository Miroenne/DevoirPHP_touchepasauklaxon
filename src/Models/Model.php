<?php

namespace App\Models;

use JsonSerializable;

abstract class Model
{

    protected readonly ?int $id;

    public function __construct(?int $id = null)
    {
        $this->id = $id;
    }

    abstract public function assertData(): bool;
    abstract protected function getKeys(): array;
    abstract protected function getValues(): array;
    abstract public static function toObject(array $data): static;
    abstract public function display(): void;

    public function toArray(): array
    {

        $keys = static::getKeys();
        $values = static::getValues();

        return array_combine($keys, $values);
    }

    public function getId(): ?int
    {
        return $this->id;
    }
}
