<?php

namespace App\Models;

use App\Models\Model;
use DateTime;
use DateTimeImmutable;
use JsonSerializable;

class Trip extends Model implements JsonSerializable
{
    protected DateTimeImmutable $departureAt;
    protected DateTimeImmutable $arrivalAt;
    protected int $availablePlaces;
    protected int $totalPlaces;
    protected int $authorId;
    protected int $fromAgencyId;
    protected int $toAgencyId;

    public function __construct(
        DateTimeImmutable $departureAt,
        DateTimeImmutable $arrivalAt,
        int $availablePlaces,
        int $totalPlaces,
        int $authorId,
        int $fromAgencyId,
        int $toAgencyId,
        ?int $id = null
    ) {
        parent::__construct($id);
        $this->departureAt = $departureAt;
        $this->arrivalAt = $arrivalAt;
        $this->availablePlaces = $availablePlaces;
        $this->totalPlaces = $totalPlaces;
        $this->authorId = $authorId;
        $this->fromAgencyId = $fromAgencyId;
        $this->toAgencyId = $toAgencyId;
    }

    public function getDepartureAt(): \DateTimeImmutable
    {
        return $this->departureAt;
    }

    public function getArrivalAt(): \DateTimeImmutable
    {
        return $this->arrivalAt;
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

    public function getFromAgencyId(): int
    {
        return $this->fromAgencyId;
    }

    public function getToAgencyId(): int
    {
        return $this->toAgencyId;
    }

    public function setDepartureAt(\DateTimeImmutable $departureAt): void
    {
        $this->departureAt = $departureAt;
    }

    public function setArrivalAt(\DateTimeImmutable $arrivalAt): void
    {
        $this->arrivalAt = $arrivalAt;
    }

    public function setAvailablePlaces(int $availablePlaces): void
    {
        if ($availablePlaces >= 0 && filter_var($availablePlaces, FILTER_VALIDATE_INT)) {
            $this->availablePlaces = $availablePlaces;
        }
    }

    public function setTotalPlaces(int $totalPlaces): void
    {
        if ($totalPlaces >= 0 && filter_var($totalPlaces, FILTER_VALIDATE_INT)) {
            $this->totalPlaces = $totalPlaces;
        }
    }

    public function setAuthorId(int $authorId): void
    {
        if ($authorId > 0 && filter_var($authorId, FILTER_VALIDATE_INT)) {
            $this->authorId = $authorId;
        }
    }

    public function setFromAgencyId(int $agencyId): void
    {
        if ($agencyId > 0 && filter_var($agencyId, FILTER_VALIDATE_INT)) {
            $this->fromAgencyId = $agencyId;
        }
    }

    public function setToAgencyId(int $agencyId): void
    {
        if ($agencyId > 0 && filter_var($agencyId, FILTER_VALIDATE_INT)) {
            $this->toAgencyId = $agencyId;
        }
    }

    public function getKeys(): array
    {
        return [
            'id',
            'departure_at',
            'arrival_at',
            'available_places',
            'total_places',
            'author_id',
            'from_agency_id',
            'to_agency_id'
        ];
    }

    public function getValues(): array
    {
        return [
            self::getId(),
            self::getDepartureAt()->format('Y-m-d H:i:s'),
            self::getArrivalAt()->format('Y-m-d H:i:s'),
            self::getAvailablePlaces(),
            self::getTotalPlaces(),
            self::getAuthorId(),
            self::getFromAgencyId(),
            self::getToAgencyId(),
        ];
    }

    public static function toObject(array $data): static
    {
        return new static(
            id: $data['id'],
            departureAt: new DateTimeImmutable($data['departure_at']),
            arrivalAt: new DateTimeImmutable($data['arrival_at']),
            availablePlaces: $data['available_places'],
            totalPlaces: $data['total_places'],
            authorId: $data['author_id'],
            fromAgencyId: $data['from_agency_id'],
            toAgencyId: $data['to_agency_id']
        );
    }

    public function assertData(): bool
    {

        if (!self::getDepartureAt() instanceof DateTimeImmutable) {
            return false;
        }

        if (!self::getArrivalAt() instanceof DateTimeImmutable) {
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

        if (self::getFromAgencyId() < 0) {
            return false;
        }

        if (self::getToAgencyId() < 0) {
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
        return self::getFromAgencyId() !== self::getToAgencyId() ? true : false;
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

    public function jsonSerialize(): array
    {
        return [
            'id' => $this->getId(),
            'departureAt' => $this->getDepartureAt()->format('Y-m-d H:i:s'),
            'arrivalAt' => $this->getArrivalAt()->format('Y-m-d H:i:s'),
            'availablePlaces' => $this->getAvailablePlaces(),
            'totalPlaces' => $this->getTotalPlaces(),
            'authorId' => $this->getAuthorId(),
            'fromAgencyId' => $this->getFromAgencyId(),
            'toAgencyId' => $this->getToAgencyId()
        ];
    }
}
