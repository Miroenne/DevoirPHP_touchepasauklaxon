<?php

declare(strict_types=1);

namespace App\Models;

use App\Models\Model;
use BcMath\Number;
use JsonSerializable;
use Override;

class Agency extends Model implements JsonSerializable
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

    public function jsonSerialize(): array
    {
        return [
            'id' => $this->getId(),
            'name' => $this->getName()
        ];
    }

    public function display(): void
    {
        echo 'Agence de : ' . $this->getName() . ', ID : ' . $this->getId() . '<br><br>';
    }
}
