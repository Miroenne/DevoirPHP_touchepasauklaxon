<?php

namespace App\Models;

require __DIR__ . ('/Model.php');

use DateTimeImmutable;
use JsonSerializable;
use Override;

class Trip extends Model
{
    protected DateTimeImmutable $departureDateTime;
    protected DateTimeImmutable $arrivalDateTime;
    protected int $availablePlaces;
    protected int $totalPlaces;
    protected int $authorId;
    protected int $departureAgencyId;
    protected int $arrivalAgencyId;

    public function __construct(
        DateTimeImmutable $departureDateTime,
        DateTimeImmutable $arrivalDateTime,
        int $availablePlaces,
        int $totalPlaces,
        int $authorId,
        int $departureAgencyId,
        int $arrivalAgencyId,
        ?int $id = null
    ) {
        parent::__construct($id);
        $this->departureDateTime = $departureDateTime;
        $this->arrivalDateTime = $arrivalDateTime;
        $this->availablePlaces = $availablePlaces;
        $this->totalPlaces = $totalPlaces;
        $this->departureAgencyId = $departureAgencyId;
        $this->arrivalAgencyId = $arrivalAgencyId;
    }

    public function getDepartureDateTime(): \DateTimeImmutable
    {
        return $this->departureDateTime;
    }

    public function getArrivalDateTime(): \DateTimeImmutable
    {
        return $this->arrivalDateTime;
    }

    public function getAvailablePlaces(): int
    {
        return $this->availablePlaces;
    }

    public function getTotalPlaces(): int
    {
        return $this->totalPlaces;
    }

    public function getAuthorId(): int
    {
        return $this->authorId;
    }

    public function getDepartureAgencyId(): int
    {
        return $this->departureAgencyId;
    }

    public function getArrivalAgencyId(): int
    {
        return $this->arrivalAgencyId;
    }

    protected function getKeys(): array
    {
        return [
            'id',
            'departureDateTime',
            'arrivalDateTime',
            'availablePlaces',
            'totalPlaces',
            'authorId',
            'departureAgencyId',
            'arrivalAgencyId'
        ];
    }

    protected function getValues(): array
    {
        return [
            self::getId(),
            self::getDepartureDateTime(),
            self::getArrivalDateTime(),
            self::getAvailablePlaces(),
            self::getTotalPlaces(),
            self::getAuthorId(),
            self::getDepartureAgencyId(),
            self::getArrivalAgencyId(),
        ];
    }

    public static function toObject(array $data): static
    {
        return new static(
            id: $data['id'],
            departureDateTime: $data['departureDateTime'],
            arrivalDateTime: $data['arrvialDateTime'],
            availablePlaces: $data['availablePlaces'],
            totalPlaces: $data['totalPlaces'],
            authorId: $data['authorId'],
            departureAgencyId: $data['departureAgencyId'],
            arrivalAgencyId: $data['arrivalAgencyId']
        );
    }

    public function assertData(): bool
    {

        if (!self::getDepartureDateTime() instanceof DateTimeImmutable) {
            return false;
        }

        if (!self::getArrivalDateTime() instanceof DateTimeImmutable) {
            return false;
        }

        if (self::getAvailablePlaces() < 0) {
            return false;
        }

        if (self::getTotalPlaces() < 0) {
            return false;
        }

        if (self::getAuthorId() < 0) {
            return false;
        }

        if (self::getDepartureAgencyId() < 0) {
            return false;
        }

        if (self::getArrivalAgencyId() < 0) {
            return false;
        }

        return true;
    }

    public function assertPlaces(): bool
    {
        return self::getAvailablePlaces() < self::getTotalPlaces() ? true : false;
    }

    public function assertAgencies(): bool
    {
        return self::getDepartureAgencyId() !== self::getArrivalAgencyId() ? true : false;
    }

    public function assertAuthor(int $author, bool $admin): bool
    {
        if ($author !== self::getAuthorId() && !$admin) {
            return false;
        }
        return true;
    }
}
