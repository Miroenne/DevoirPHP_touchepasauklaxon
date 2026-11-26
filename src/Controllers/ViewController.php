<?php

namespace App\Controllers;

use App\Services\{UserService, AgencyService, TripService};
use App\Exceptions\{
    UnthorizedException,
    ForbiddenException,
    ResourceNotFoundException,
    InvalidArgumentException,
    InvalidCredentialsException,
    ExceptionSerialize
};
use App\Models\User;

class ViewController
{
    protected ExceptionSerialize $serialize;

    private const VIEW_PATH = __DIR__ . '/../Views/';

    private function render(string $view, array $data = []): string
    {
        $__file = self::VIEW_PATH . $view . '.php';

        if (!is_file($__file)) {
            throw new \RuntimeException("View was not found : $view");
        }

        ob_start();
        try {
            (static function (string $__file, array $__data): void {
                extract($__data, EXTR_SKIP);
                require $__file;
            })($__file, $data);
            return ob_get_contents();
        } finally {
            ob_end_clean();
        }
    }

    private function userCheck(): ?User
    {
        $userId = $_SESSION['userId'] ?? null;
        $currentUser = null;

        if ($userId === null) {
            return null;
        }


        try {
            return $currentUser = (new UserService())->findByIdService($userId, $userId);
        } catch (ResourceNotFoundException) {
            unset($_SESSION['userId'], $_SESSION['csrfToken']);
            return null;
        }
    }



    public function dashboard(): string
    {

        $currentUser = $this->userCheck();

        return $this->render('dashboard', [
            'trips' => (new TripService())->findAvailablesTripsService(),
            'currentUser' => $currentUser
        ]);
    }

    public function test(): string
    {
        return $this->render('test');
    }

    public function login(): string
    {
        return $this->render('login');
    }

    public function users(): string
    {
        $currentUser = $this->userCheck();

        if ($currentUser === null) {
            http_response_code(401);
            return $this->render('error', [
                'message' => 'Vous devez être connecté',
                'code' => 401
            ]);
        }

        $userId = $currentUser->getId();

        if ($currentUser->getIsAdmin() !== true) {
            http_response_code(403);
            return $this->render('error', ['message' => 'Accès réservé aux administrateurs', 'code' => 403]);
        }


        return $this->render('users', [
            'users' => (new UserService())->findAllService($userId),
            'currentUser' => $currentUser
        ]);
    }

    public function trips(): string
    {
        $currentUser = $this->userCheck();

        if ($currentUser === null) {
            http_response_code(401);
            return $this->render('error', [
                'message' => 'Vous devez être connecté',
                'code' => 401
            ]);
        }

        $userId = $currentUser->getId();

        if ($currentUser->getIsAdmin() !== true) {
            http_response_code(403);
            return $this->render('error', ['message' => 'Accès réservé aux administrateurs', 'code' => 403]);
        }

        return $this->render('trips', [
            'trips' => (new TripService())->findAllService($userId),
            'currentUser' => $currentUser
        ]);
    }

    public function agencies(): string
    {
        $currentUser = $this->userCheck();

        if ($currentUser === null) {
            http_response_code(401);
            return $this->render('error', [
                'message' => 'Vous devez être connecté',
                'code' => 401
            ]);
        }

        $userId = $currentUser->getId();

        if ($currentUser->getIsAdmin() !== true) {
            http_response_code(403);
            return $this->render('error', ['message' => 'Accès réservé aux administrateurs', 'code' => 403]);
        }

        return $this->render('agencies', [
            'agencies' => (new AgencyService())->findAllService($userId),
            'currentUser' => $currentUser
        ]);
    }

    public function loginSubmit(): string
    {
        $service = new UserService();
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';

        try {
            $connect = $service->login($email, $password);

            session_regenerate_id(true);

            $_SESSION['userId'] = $connect['user']->getId();
            $_SESSION['csrfToken'] = $connect['csrfToken'];

            header('Location: /');
            exit;
        } catch (InvalidCredentialsException $e) {
            return $this->render('login', ['error' => 'Email ou mot de passe incorrect']);
        }
    }

    public function logoutSubmit(): string
    {
        $_SESSION = [];

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

        session_destroy();
        header('Location: /');
        exit;
    }
}
