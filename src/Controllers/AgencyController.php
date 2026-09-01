<?php

namespace App\Controllers;

use App\Services\AgencyService;
use App\Exceptions\{
    ForbiddenException,
    RessourceNotFoundException,
    UnthorizedException,
    InvalidArgumentException,
    ExceptionSerialize
};
use JsonSerializable;
use App\Models\Agency;
use Override;
use RuntimeException;

class AgencyController extends Controller
{

    protected AgencyService $agencyService;

    public function __construct()
    {
        parent::__construct();
        $this->agencyService = new AgencyService;
    }

    public function getService(): object
    {
        return new AgencyService;
    }

    public function createController(): string
    {
        $name = $_POST['name'];
        $userId = $_POST['userId'] ?? null;
        $newAgency = new Agency($name);

        try {
            $this->agencyService->createService($newAgency, $userId);

            $result = [
                'message' => 'Agence créée avec succès',
                'responseCode' => 200
            ];

            return json_encode($result);
        } catch (UnthorizedException $e) {
            $error = $this->serialize->serializeException(
                $e->getMessage(),
                $e->getStatusCode()
            );
            return json_encode($error);
        } catch (RessourceNotFoundException $e) {
            $error = $this->serialize->serializeException(
                $e->getMessage(),
                $e->getStatusCode()
            );
            return json_encode($error);
        } catch (ForbiddenException $e) {
            $error = $this->serialize->serializeException(
                $e->getMessage(),
                $e->getStatusCode()
            );
            return json_encode($error);
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
    }

    public function findByNameController(): array
    {

        $name = $_POST['name'];

        try {
            $result = $this->agencyService->findByNameService($name);

            foreach ($result as $agency) {
                $jsonAgency = json_encode($agency);
                $jsonAgencies[] = $jsonAgency;
            }
            return ['agencies' => $jsonAgencies, 'responseCode' => 200];
        } catch (RessourceNotFoundException $e) {
            $error = $this->serialize->serializeException(
                $e->getMessage(),
                $e->getStatusCode()
            );
            return $error;
        }
    }

    public function updateController(): string
    {

        $id = $_POST['id'];
        $name = $_POST['name'];
        $userId = $_POST['userId'] ?? null;
        $updateAgency = new Agency($name, $id);

        try {
            $this->agencyService->updateService($updateAgency, $userId);

            $result = [
                'message' => 'Agence mise à jour avec succès',
                'responseCode' => 200
            ];

            return json_encode($result);
        } catch (UnthorizedException $e) {
            $error = $this->serialize->serializeException(
                $e->getMessage(),
                $e->getStatusCode()
            );
            return json_encode($error);
        } catch (RessourceNotFoundException $e) {
            $error = $this->serialize->serializeException(
                $e->getMessage(),
                $e->getStatusCode()
            );
            return json_encode($error);
        } catch (ForbiddenException $e) {
            $error = $this->serialize->serializeException(
                $e->getMessage(),
                $e->getStatusCode()
            );
            return json_encode($error);
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
    }
}
