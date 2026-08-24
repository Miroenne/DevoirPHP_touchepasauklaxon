<?php

namespace App\Models;

use App\Models\Model;
use DateTime;
use DateTimeImmutable;
use JsonSerializable;

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
        $this->authorId = $authorId;
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

    public function setDepartureDateTime(\DateTimeImmutable $departureDateTime): void
    {
        $this->departureDateTime = $departureDateTime;
    }

    public function setArrivalDateTime(\DateTimeImmutable $arrivalDateTime): void
    {
        $this->arrivalDateTime = $arrivalDateTime;
    }

    public function setAvailablePlaces(int $availablePlaces): void
    {
        if ($availablePlaces >= 0) {
            $this->availablePlaces = $availablePlaces;
        }
    }

    public function setTotalPlaces(int $totalPlaces): void
    {
        if ($totalPlaces >= 0) {
            $this->totalPlaces = $totalPlaces;
        }
    }

    public function setAuthorId(int $authorId): void
    {
        if ($authorId > 0) {
            $this->authorId = $authorId;
        }
    }

    public function setDepartureAgnecyId(int $agencyId): void
    {
        if ($agencyId > 0) {
            $this->departureAgencyId = $agencyId;
        }
    }

    public function setArrivalAgencyId(int $agencyId): void
    {
        if ($agencyId > 0) {
            $this->arrivalAgencyId = $agencyId;
        }
    }

    public function getKeys(): array
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

    public function getValues(): array
    {
        return [
            self::getId(),
            self::getDepartureDateTime()->format('Y-m-d H:i:s'),
            self::getArrivalDateTime()->format('Y-m-d H:i:s'),
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
            departureDateTime: new DateTimeImmutable($data['departureDateTime']),
            arrivalDateTime: new DateTimeImmutable($data['arrivalDateTime']),
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

    public function assertDateTime(\DateTimeImmutable $departure, \DateTimeImmutable $arrival)
    {
        return $departure < $arrival ? true : false;
    }

    public function assertAuthor(int $author, bool $admin): bool
    {
        if ($author !== self::getAuthorId() && !$admin) {
            return false;
        }
        return true;
    }
}
