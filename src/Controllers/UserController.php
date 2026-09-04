<?php

namespace App\Controllers;

use App\Exceptions\{ForbiddenException, UnthorizedException, InvalidArgumentException, InvalidCredentialsException, RessourceNotFoundException};
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
        return $this->toJson($res);
    }

    #[Override]
    public function updateController(): string
    {
        $res = ['message' => 'Not implemented', 'responseCode' => 501];
        return $this->toJson($res);
    }

    public function findByEmailController(): string
    {

        $service = new UserService();

        $email = $_GET['email'];
        $userId = $_GET['userId'] ?? null;

        try {
            $user = $service->findByEmailService($email, $userId);

            $result = ['user' => $user, 'responseCode' => 200];

            return $this->toJson($result);
        } catch (UnthorizedException $e) {
            $error = $this->serialize->serializeException($e->getMessage(), $e->getStatusCode());
            return $this->toJson($error);
        } catch (InvalidArgumentException $e) {
            $error = $this->serialize->serializeException($e->getMessage(), $e->getStatusCode());
            return $this->toJson($error);
        } catch (ForbiddenException $e) {
            $error = $this->serialize->serializeException($e->getMessage(), $e->getStatusCode());
            return $this->toJson($error);
        } catch (RessourceNotFoundException $e) {
            $error = $this->serialize->serializeException($e->getMessage(), $e->getStatusCode());
            return $this->toJson($error);
        }
    }

    public function loginController(): string
    {

        $service = new UserService;
        $email = $_POST['email'];
        $password = $_POST['password'];

        try {
            $connect = $service->login($email, $password);

            setcookie(
                'csrf-token',
                $connect['csrfToken'],
                [
                    'expires' => time() + 60 * 60 * 24,
                    'path' => '/',
                    'httponly' => true,
                    'secure' => true,
                    'samesite' => 'none'
                ]
            );

            $user = $connect['user'];
            $result = ['user' => $user, 'responseCode' => 200];

            return $this->toJson($result);
        } catch (InvalidCredentialsException $e) {
            $error = $this->serialize->serializeException($e->getMessage(), $e->getStatusCode());
            return $this->toJson($error);
        }
    }

    public function logoutController(): string
    {
        $service = new UserService();
        $id = $_POST['id'];
        $userId = $_POST['userId'];

        if ($id !== $userId) {
            $error = [
                'message' => "Une erreur s'est produite",
                'responseCode' => '400'
            ];

            return $this->toJson($error);
        }

        try {
            $user = $service->findByIdService($id, $userId);

            if (ini_get('session.use_cookies')) {
                $p = session_get_cookie_params();
                setcookie(
                    session_name(),
                    '',
                    [
                        'expires' => time() - 42000,
                        'path' => $p['path'],
                        'domain' => $p['domain'],
                        'secure' => $p['secure'],
                        'httponly' => $p['httponly'],
                        'samesite' => $p['samesite']
                    ]
                );
            }

            setcookie(
                'csrf-token',
                '',
                [
                    'expires' => time() - 3600,
                    'path' => '/',
                    'httponly' => true,
                    'secure' => true,
                    'samesite' => 'none'
                ]
            );

            session_destroy();
            $res = ['message' => 'Déconnexion réussie', 'responseCode' => 200];
            return $this->toJson($res);
        } catch (UnthorizedException $e) {
            $error = $this->serialize->serializeException($e->getMessage(), $e->getStatusCode());
            return $this->toJson($error);
        } catch (ForbiddenException $e) {
            $error = $this->serialize->serializeException($e->getMessage(), $e->getStatusCode());
            return $this->toJson($error);
        } catch (RessourceNotFoundException $e) {
            $error = $this->serialize->serializeException($e->getMessage(), $e->getStatusCode());
            return $this->toJson($error);
        }
    }
}
