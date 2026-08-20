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
    abstract protected static function getKeys(): array;
    abstract protected function getValues(): array;
    abstract public static function toObject(array $data): static;

    public function toArray(): array
    {

        $keys = static::getKeys();
        $values = static::getValues();

        return array_combine($keys, $values);
    }

    public function toClass(array $array): static
    {
        $string = [];

        foreach ($array as $key => $value) {
            $string[] = $key . ' : ' . match (true) {
            };
        }

        $result = implode(', ', $string);

        return new static($result);
    }

    public function getId(): ?int
    {
        return $this->id;
    }
}
