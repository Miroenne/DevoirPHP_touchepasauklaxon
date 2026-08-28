<?php

namespace App\DTO;

use App\Models\Agency;
use App\Models\Trip;
use App\Models\User;
use JsonSerializable;

final class TripDetails implements JsonSerializable
{

    public function __construct(
        public readonly Trip $trip,
        public readonly User $author,
        public readonly Agency $departureAgency,
        public readonly Agency $arrivalAgency
    ) {}

    public function jsonSerialize(): array
    {
        return [
            'trip' => $this->trip,
            'author' => $this->author,
            'departureAgency' => $this->departureAgency,
            'arrivalAgency' => $this->arrivalAgency
        ];
    }
}
