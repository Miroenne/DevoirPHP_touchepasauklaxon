<?php

namespace App\Controllers;

use App\Controllers\Controller;
use App\Exceptions\{
    ForbiddenException,
    RessourceNotFoundException,
    UnthorizedException,
    InvalidArgumentException,
    ExceptionSerialize
};
use JsonSerializable;
use App\Models\Trip;
use App\Services\{TripService, AgencyService};
use DateTimeImmutable;
use RuntimeException;

class TripController extends Controller
{


    protected ExceptionSerialize $serialize;
    protected TripService $tripService;
    protected AgencyService $agencyService;

    public function __construct()
    {
        parent::__construct();
        $this->tripService = new TripService;
        $this->agencyService = new AgencyService;
    }

    public function getService(): object
    {
        return new TripService;
    }

    public function createController(): string
    {

        $departureDate = $_POST['departureDate'];
        $departureTime = $_POST['departureTime'];
        $arrivalDate = $_POST['arrivalDate'];
        $arrivalTime = $_POST['arrivalTime'];

        $departureAt = new DateTimeImmutable($departureDate . $departureTime);
        $arrvialAt = new DateTimeImmutable($arrivalDate . $arrivalTime);

        $availablePlaces = $_POST['availablePlaces'];
        $totalPlaces = $_POST['totalPlaces'];
        $authorId = $_POST['userId'] ?? null;
        $fromAgency = $_POST['fromAgency'];
        $toAgency = $_POST['toAgency'];
        $fromAgencyId = null;
        $toAgencyId = null;

        try {
            $fromAgencies = $this->agencyService->findByNameService($_POST['fromAgency']);

            foreach ($fromAgencies as $fromAgency) {
                if (strtolower($fromAgency->getName()) === strtolower($_POST['fromAgency'])) {
                    $fromAgencyId = $fromAgency->getId();
                }
            }
        } catch (RessourceNotFoundException $e) {
            $error = $this->serialize->serializeException(
                'Departure agency (' . $_POST['fromAgency'] . ') not was not found',
                404
            );

            return json_encode($error);
        }

        try {
            $toAgencies = $this->agencyService->findByNameService($_POST['toAgency']);


            foreach ($toAgencies as $toAgency) {
                if (strtolower($toAgency->getName()) === strtolower($_POST['toAgency'])) {
                    $toAgencyId = $toAgency->getId();
                }
            }
        } catch (RessourceNotFoundException $e) {
            $error = $this->serialize->serializeException(
                'Arrival agency (' . $_POST['toAgency'] . ') not was not found',
                404
            );

            return json_encode($error);
        }

        $trip = new Trip(
            $departureAt,
            $arrvialAt,
            $availablePlaces,
            $totalPlaces,
            $authorId,
            $fromAgencyId,
            $toAgencyId
        );

        try {
            $this->tripService->createService($trip, $authorId);
        } catch (InvalidArgumentException $e) {
            $error = $this->serialize->serializeException(
                $e->getMessage(),
                $e->getStatusCode()
            );
            return json_encode($error);
        } catch (RuntimeException $e) {
            $error = $this->serialize->serializeException(
                $e->getMessage(),
                $e->getCode()
            );
            return json_encode($error);
        }
        $result = [
            'message' => 'Trajet créé avec succès',
            'responseCode' => 200
        ];

        return json_encode($result);
    }

    public function updateController(): string
    {
        $tripId = $_POST['tripId'];
        $departureDate = $_POST['departureDate'];
        $departureTime = $_POST['departureTime'];
        $arrivalDate = $_POST['arrivalDate'];
        $arrivalTime = $_POST['arrivalTime'];

        $departureAt = new DateTimeImmutable($departureDate . $departureTime);
        $arrvialAt = new DateTimeImmutable($arrivalDate, $arrivalTime);

        $availablePlaces = $_POST['availablePlaces'];
        $totalPlaces = $_POST['totalPlaces'];
        $authorId = $_POST['userId'];

        try {
            $fromAgencies = $this->agencyService->findByNameService($_POST['fromAgency']);

            foreach ($fromAgencies as $fromAgency) {
                if (strtolower($fromAgency->getName() === strtolower($_POST['fromAgency']))) {
                    $fromAgencyId = $fromAgency->getId();
                }
            }
        } catch (RessourceNotFoundException $e) {
            $error = $this->serialize->serializeException(
                'Departure agency (' . $_POST['fromAgency'] . ') not was not found',
                404
            );

            return json_encode($error);
        }

        try {
            $toAgencies = $this->agencyService->findByNameService($_POST['toAgency']);

            foreach ($toAgencies as $toAgency) {
                if (strtolower($toAgency->getName() === strtolower($_POST['toAgency']))) {
                    $toAgencyId = $toAgency->getId();
                }
            }
        } catch (RessourceNotFoundException $e) {
            $error = $this->serialize->serializeException(
                'Arrival agency (' . $_POST['toAgency'] . ') not was not found',
                404
            );

            return json_encode($error);
        }

        $trip = new Trip(
            id: $tripId,
            departureAt: $departureAt,
            arrivalAt: $arrvialAt,
            availablePlaces: $availablePlaces,
            totalPlaces: $totalPlaces,
            authorId: $authorId,
            fromAgencyId: $fromAgencyId,
            toAgencyId: $toAgencyId
        );

        try {
            $this->tripService->createService($trip, $authorId);
        } catch (InvalidArgumentException $e) {
            $error = $this->serialize->serializeException(
                $e->getMessage(),
                $e->getStatusCode()
            );
            return json_encode($error);
        } catch (RuntimeException $e) {
            $error = $this->serialize->serializeException(
                $e->getMessage(),
                $e->getCode()
            );
            return json_encode($error);
        }
        $result = [
            'message' => 'Trajet mis à jour avec succès',
            'responseCode' => 200
        ];

        return json_encode($result);
    }
}
