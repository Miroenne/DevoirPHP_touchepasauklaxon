<?php

namespace App\Services;

use App\DTO\TripDetails;
use App\Exceptions\ResourceNotFoundException;
use App\Services\Service;
use App\Models\{Trip, Agency, User};
use App\Repositories\{AgencyRepository, TripRepository, UserRepository};
use DateTimeImmutable;
use App\Exceptions\InvalidArgumentException;
use App\Exceptions\UnthorizedException;

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
        $trip = $this->repository->findById($id) ?? throw new ResourceNotFoundException();

        return new TripDetails(
            trip: $trip,
            author: $userRepository->findById($trip->getAuthorId()),
            departureAgency: $agencyRepository->findById($trip->getFromAgencyId()),
            arrivalAgency: $agencyRepository->findById($trip->getToAgencyId())
        );
    }

    public function createService(object $trip, ?int $userId = null): bool
    {

        if ($this->isExisting($trip) === true) {
            throw new InvalidArgumentException("There's already an existing trip with availables places");
        }

        return parent::createService($trip, $userId);
    }

    public function updateService(object $trip, ?int $userId = null): bool
    {
        if ($this->isExisting($trip) === true) {
            throw new InvalidArgumentException("There's already an existing trip with availables places");
        }

        return parent::updateService($trip, $userId);
    }

    public function findAllService(?int $id = null): array
    {
        $trips = [];

        $foundTrips = $this->repository->findAll();

        foreach ($foundTrips as $foundTrip) {
            $trip = $this->toDetailsService($foundTrip);
            $trips[] = $trip;
        }

        if (!isset($trips)) {
            throw new ResourceNotFoundException();
        }

        return $trips;
    }

    public function findByIdService(int $id, ?int $userId = null): TripDetails
    {

        if (!isset($userId)) {
            throw new UnthorizedException('Connection required');
        }

        $userRepository = new UserRepository();
        $user = $userRepository->findById($userId);

        if (!isset($userId)) {
            throw new ResourceNotFoundException();
        }

        $trip = $this->repository->findById($id);

        if (!isset($trip)) {
            throw new ResourceNotFoundException();
        }

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

        $tripRepository = new TripRepository();
        $availablesTrips = $tripRepository->findAvailablesTrips();
        $departure = new DateTimeImmutable($trip->getDepartureAt()->format('Y-m-d'));
        $arrival = new DateTimeImmutable($trip->getArrivalAt()->format('Y-m-d'));

        $usersCount = $trip->getTotalPlaces() - $trip->getAvailablePlaces();

        $result = false;

        foreach ($availablesTrips as $availableTrip) {

            $existingDeparture = new DateTimeImmutable($availableTrip->getDepartureAt()->format('Y-m-d'));
            $existingArrival = new DateTimeImmutable($availableTrip->getArrivalAt()->format('Y-m-d'));

            if (
                $trip->getFromAgencyId() === $availableTrip->getFromAgencyId() &&
                $trip->getToAgencyId() === $availableTrip->getToAgencyId()
            ) {
                if ($departure == $existingDeparture && $arrival == $existingArrival) {

                    if (
                        $availableTrip->getAvailablePlaces() > 0 &&
                        $availableTrip->getAvailablePlaces() >= $usersCount
                    ) {
                        $result = true;
                    }
                }
            }
        }
        return $result;
    }
}
