<?php

namespace App\Middlewares;

use App\Exceptions\{UnthorizedException, ForbiddenException};
use App\Repositories\UserRepository;

class Middlewares{
    
    public function run(string $name): void {
        match($name){
            'auth' => $this->auth(),
            'admin' => $this->admin(),
            'csrf' => $this->csrf(),
            default => throw new \RuntimeException("Middleware $name not found")
        };
    }

    private function auth(): void {
        $userId = $_SESSION['userId'] ?? null;

        if($userId === null){
            throw new UnthorizedException('Authentification required');
        }

        $_GET['userId'] = $userId;
        $_POST['userId'] = $userId;
    }

    private function csrf(): void {
        $sent = $_SERVER['HTTP_X_CSRF_TOKEN'] ?? '';
        $stored = $_SESSION['csrfToken'] ?? '';

        if($sent === '' || !hash_equals($stored, $sent)){
            throw new ForbiddenException('Invalid CSRF token');
        }
    }

    private function admin(): void {
        $user = (new UserRepository())->findById($_SESSION['userId']);

        if($user === null || $user->getIsAdmin() === false){
            throw new ForbiddenException('Admin privileges required');
        }
    }
}