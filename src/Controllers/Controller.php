<?php

namespace App\Controllers;

use App\Exceptions\{
    ForbiddenException,
    RessourceNotFoundException,
    UnthorizedException,
    ExceptionSerialize
};
use App\Services\Service;
use Exception;
use JsonSerializable;
use RuntimeException;
use App\Models\{Agency, Trip, User};
use App\Services\AgencyService;
use App\Services\UserService;

abstract class Controller
{

    protected Service $service;
    protected ExceptionSerialize $serialize;

    public function __construct()
    {
        $this->service = $this->getService();
        $this->serialize = new ExceptionSerialize();
    }

    abstract protected function getService(): object;

    abstract public function createController(): string;
    abstract public function updateController(): string;

    public function findAllController(): array
    {
        $userId = $_GET['userId'] ?? null;

        try {
            $entities = $this->service->findAllService($userId);

            if ($entities) {
                foreach ($entities as $entity) {
                    $jsonEntity = json_encode($entity);
                    $jsonEntities[] = $jsonEntity;
                }
            }

            return ['entities' => $jsonEntities, 'responseCode' => 200];
        } catch (UnthorizedException $e) {

            return $this->serialize->serializeException($e->getMessage(), $e->getStatusCode());
        } catch (ForbiddenException $e) {

            return $this->serialize->serializeException($e->getMessage(), $e->getStatusCode());
        } catch (RessourceNotFoundException $e) {

            return $this->serialize->serializeException($e->getMessage(), $e->getStatusCode());
        }
    }

    public function findByIdController(): string
    {
        $searchId = $_GET['id'];
        $userId = $_GET['userId'] ?? null;

        try {
            $entity = $this->service->findByIdService($searchId, $userId);

            $result = ['entity' => $entity, 'responseCode' => 200];

            return json_encode($result);
        } catch (ForbiddenException $e) {

            $error = $this->serialize->serializeException($e->getMessage(), $e->getStatusCode());
            return json_encode($error);
        } catch (RessourceNotFoundException $e) {
            $error = $this->serialize->serializeException($e->getMessage(), $e->getStatusCode());
            return json_encode($error);
        } catch (UnthorizedException $e) {
            $error = $this->serialize->serializeException($e->getMessage(), $e->getStatusCode());
            return json_encode($error);
        }
    }

    public function deleteController(): string
    {
        $deleteId = $_POST['id'];
        $userId = $_POST['userId'] ?? null;

        try {

            if ($this instanceof UserController) {
                throw new \Exception('Not implemented');
            } elseif ($this instanceof TripController) {
                $this->service->deleteService($deleteId, $userId);
                $result = [
                    'message' => 'Trajet supprimé',
                    'responseCode' => 200
                ];
            } elseif ($this instanceof AgencyController) {
                $this->service->deleteService($deleteId, $userId);
                $result = [
                    'message' => 'Agence supprimée',
                    'responseCode' => 200
                ];
            }

            return json_encode($result);
        } catch (RuntimeException $e) {
            $error = $this->serialize->serializeException($e->getMessage(), $e->getCode());
            return json_encode($error);
        } catch (ForbiddenException $e) {
            $error = $this->serialize->serializeException($e->getMessage(), $e->getStatusCode());
            return json_encode($error);
        } catch (RessourceNotFoundException $e) {
            $error = $this->serialize->serializeException($e->getMessage(), $e->getStatusCode());
            return json_encode($error);
        }
    }
}
