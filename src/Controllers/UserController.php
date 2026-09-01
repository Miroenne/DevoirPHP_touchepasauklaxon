<?php

namespace App\Controllers;

use App\Exceptions\{ForbiddenException, UnthorizedException, InvalidArgumentException, RessourceNotFoundException};
use App\Services\UserService;
use Override;

class UserController extends Controller
{


    public function getService(): object
    {
        return new UserService;
    }

    #[Override]
    public function createController(): string
    {
        $res = ['message' => 'Not implemented', 'responseCode' => 501];
        return json_encode($res);
    }

    #[Override]
    public function updateController(): string
    {
        $res = ['message' => 'Not implemented', 'responseCode' => 501];
        return json_encode($res);
    }

    public function findByEmailController(): string
    {

        $service = new UserService();

        $email = $_GET['email'];
        $userId = $_GET['userId'] ?? null;

        try {
            $user = $service->findByEmailService($email, $userId);

            $result = ['user' => $user, 'responseCode' => 200];

            return json_encode($result);
        } catch (UnthorizedException $e) {
            $error = $this->serialize->serializeException($e->getMessage(), $e->getStatusCode());
            return json_encode($error);
        } catch (InvalidArgumentException $e) {
            $error = $this->serialize->serializeException($e->getMessage(), $e->getStatusCode());
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
