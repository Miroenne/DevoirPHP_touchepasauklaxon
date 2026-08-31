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

        if (isset($_SESSION['userId'])) {
            $userId = $_SESSION['userId'];
        }

        try {
            $entities = $this->service->findAllService($userId);

            foreach ($entities as $entity) {
                $jsonEntity = json_encode($entity);
                $jsonEntities[] = $jsonEntity;
            }

            return $jsonEntities;
        } catch (UnthorizedException $e) {

            return $this->serialize->serializeException($e->getMessage(), $e->getCode());
        } catch (ForbiddenException $e) {

            return $this->serialize->serializeException($e->getMessage(), $e->getCode());
        } catch (RessourceNotFoundException $e) {

            return $this->serialize->serializeException($e->getMessage(), $e->getCode());
        }
    }

    public function findByIdController(): string
    {

        if (isset($_POST['id']) && isset($_SESSION['userId'])) {
            $searchId = $_POST['id'];
            $userId = $_SESSION['userId'];
        }

        try {
            $entity = $this->service->findByIdService($searchId, $userId);

            return json_encode($entity);
        } catch (ForbiddenException $e) {

            $error = $this->serialize->serializeException($e->getMessage(), $e->getCode());
            return json_encode($error);
        } catch (RessourceNotFoundException $e) {
            $error = $this->serialize->serializeException($e->getMessage(), $e->getCode());
            return json_encode($error);
        }
    }

    public function deleteController(): string
    {

        if (isset($_POST['id']) && isset($_SESSION['userId'])) {
            $deleteId = $_POST['id'];
            $userId = $_SESSION['userId'];
        }
        try {
            $deleteEntity = $this->service->deleteService($deleteId, $userId);

            if ($deleteEntity instanceof User) {
                $result = [
                    'message' => 'Utilisateur supprimé',
                    'statusCode' => 200
                ];
            } elseif ($deleteEntity instanceof Trip) {
                $result = [
                    'message' => 'Trajet supprimé',
                    'statusCode' => 200
                ];
            } elseif ($deleteEntity instanceof Agency) {
                $result = [
                    'message' => 'Agence supprimée',
                    'statusCode' => 200
                ];
            }

            return json_encode($result);
        } catch (RuntimeException $e) {
            $error = $this->serialize->serializeException($e->getMessage(), $e->getCode());
            return json_encode($error);
        } catch (ForbiddenException $e) {
            $error = $this->serialize->serializeException($e->getMessage(), $e->getCode());
            return json_encode($error);
        } catch (RessourceNotFoundException $e) {
            $error = $this->serialize->serializeException($e->getMessage(), $e->getCode());
            return json_encode($error);
        }
    }
}
