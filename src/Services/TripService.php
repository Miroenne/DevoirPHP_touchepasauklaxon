<?php

namespace App\Services;

use App\DTO\TripDetails;
use App\Exceptions\RessourceNotFoundException;
use App\Services\Service;
use App\Models\{Trip, Agency, User};
use App\Repositories\{AgencyRepository, TripRepository, UserRepository};
use DateTimeImmutable;
use InvalidArgumentException;
use Override;

class TripService extends Service
{

    protected function getRepository(): object
    {
        return new TripRepository;
    }

    public function __construct()
    {
        parent::__construct();
    }

    protected function toDetailsService(Trip $trip): TripDetails
    {
        $id = $trip->getId();
        $userRepository = new UserRepository();
        $agencyRepository = new AgencyRepository();
        $trip = $this->repository->findById($id) ?? throw new RessourceNotFoundException();

        return new TripDetails(
            trip: $trip,
            author: $userRepository->findById($trip->getAuthorId()),
            departureAgency: $agencyRepository->findById($trip->getFromAgencyId()),
            arrivalAgency: $agencyRepository->findById($trip->getToAgencyId())
        );
    }

    public function createService(object $trip, ?int $userId = null): bool
    {
        echo $this->isExisting($trip);
        if ($this->isExisting($trip) === true) {
            throw new InvalidArgumentException("There's already an existing trip with availables places");
        }

        return parent::createService($trip, $userId);
    }

    public function findAllService(?int $id = null): array
    {
        $trips = [];

        $foundTrips = $this->repository->findAll();

        foreach ($foundTrips as $foundTrip) {
            $trip = $this->toDetailsService($foundTrip);
            $trips[] = $trip;
        }

        return $trips;
    }

    public function findByIdService(int $id, ?int $userId = null): TripDetails
    {
        $trip = $this->repository->findById($id);

        return $this->toDetailsService($trip);
    }

    public function findAvailablesTripsService(): array
    {
        $tripRepository = $this->getRepository();
        $availablesTrips = [];

        $foundTrips = $tripRepository->findAvailablesTrips();

        foreach ($foundTrips as $foundTrip) {
            $trip = $this->toDetailsService($foundTrip);
            $availablesTrips[] = $trip;
        }
        return $availablesTrips;
    }

    protected function isExisting(Trip $trip): bool
    {

        $tripRepository = $this->getRepository();
        $existingTrips = $tripRepository->findAll();
        $departure = new DateTimeImmutable($trip->getDepartureAt()->format('Y-m-d'));
        $arrival = new DateTimeImmutable($trip->getArrivalAt()->format('Y-m-d'));

        $result = false;

        foreach ($existingTrips as $existingTrip) {

            $existingDeparture = new DateTimeImmutable($existingTrip->getDepartureAt()->format('Y-m-d'));
            $existingArrival = new DateTimeImmutable($existingTrip->getArrivalAt()->format('Y-m-d'));

            if (
                $trip->getFromAgencyId() === $existingTrip->getFromAgencyId() &&
                $trip->getToAgencyId() === $existingTrip->getToAgencyId()
            ) {
                if ($departure == $existingDeparture && $arrival == $existingArrival) {
                    if ($existingTrip->getAvailablePlaces() > 0) {
                        $result = true;
                    }
                }
            }
        }

        return $result;
    }
}
