<?php

namespace App\DTO;

use App\Models\{Agency, Trip, User};
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


    public function display(): void
    {
        echo 'Trajet au départ de : ' . $this->departureAgency->getName() . ' et à destination de : '
            . $this->arrivalAgency->getName() . '<br>';
        echo 'Date et heure de départ du trajet : ' . $this->trip->getDepartureAt()->format("d/m/Y") . ' à ' .
            $this->trip->getDepartureAt()->format("H:i") . '<br>';
        echo "Date et heure d'arrivée du trajet : " . $this->trip->getArrivalAt()->format("d/m/Y") . ' à ' .
            $this->trip->getArrivalAt()->format("H:i") . '<br>';
        echo 'Nombre de place(s) disponible(s) : ' . $this->trip->getAvailablePlaces() .
            '/' . $this->trip->getTotalPlaces() . '<br>';
        echo 'Personne à contacter : ' . $this->author->getfirstName() . ' ' .
            $this->author->getlastName() . ' au ' . $this->author->getPhoneNumber() . '<br><br>';
    }
}
