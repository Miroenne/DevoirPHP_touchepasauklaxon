<?php

namespace App\Db;

use App\Db\Connect;
use PDO;

class FixingPassword
{
    private PDO $pdo;

    public function __construct()
    {
        $this->pdo = Connect::Connect();
    }

    public function fixing()
    {
        $stmt = $this->pdo->query("SELECT id, first_name, last_name FROM users");
        $users = $stmt->fetchAll();

        foreach ($users as $user) {
            $defaultPassword = $user['last_name'] . '@' . $user['first_name'] . 'MDP';
            $passwordHash = password_hash($defaultPassword, PASSWORD_BCRYPT);
            echo $passwordHash;

            $stmt = $this->pdo->prepare("UPDATE users SET password_hash = :passwordHash WHERE id= :id");
            $stmt->execute(['id' => $user['id'], 'passwordHash' => $passwordHash]);
        }
        echo 'Passwords fixed';
    }
}
