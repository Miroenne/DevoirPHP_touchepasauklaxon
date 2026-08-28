<?php

namespace App\Services;

use App\DTO\TripDetails;
use App\Exceptions\RessourceNotFoundException;
use App\Services\Service;
use App\Models\Trip;
use App\Repositories\AgencyRepository;
use App\Repositories\TripRepository;
use App\Repositories\UserRepository;
use App\Services\AgencyService;
use App\Services\UserService;

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

    public function findDetailsService(int $id): TripDetails
    {

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
}
