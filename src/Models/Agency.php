<?php

declare(strict_types=1);

namespace App\Models;

use App\Models\Model;
use BcMath\Number;
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
        if (trim($name) !== '');
        $this->name = $name;
    }

    public function getKeys(): array
    {
        return [
            'id',
            'name'
        ];
    }

    public function getValues(): array
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
