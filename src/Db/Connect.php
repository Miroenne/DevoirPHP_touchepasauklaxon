<?php

namespace App\Db;

use PDO;
use PDOException;
use RuntimeException;

class Connect
{

    public static function Connect()
    {

        $urlPath = $_SERVER['HTTP_HOST'];


        if (str_contains($urlPath, 'localhost')) {
            $config = parse_ini_file(__DIR__ . '/../env/.env.dev');
            $host = $config['DB_ADDRESS'] ?? '';
            $port = $config['DB_PORT'] ?? '';
            $dbName = $config['DB_DATABASE'] ?? '';
            $userName = $config['DB_USERNAME'] ?? '';
            $password = $config['DB_PASSWORD'] ?? '';
            $options = [];
        } else {
            $config = parse_ini_file(__DIR__ . '/../env/.env');
            $host = $config['DB_ADDRESS'] ?? '';
            $port = $config['DB_PORT'] ?? '';
            $dbName = $config['DB_DATABASE'] ?? '';
            $userName = $config['DB_USERNAME'] ?? '';
            $password = $config['DB_PASSWORD'] ?? '';
            $options = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                Pdo\MYSQL::ATTR_SSL_CA => $config['CA'],
                Pdo\MYSQL::ATTR_SSL_VERIFY_SERVER_CERT => true,

            ];
        }



        try {
            $pdo = new PDO("mysql:host=$host; port=$port; dbname=$dbName; charset=utf8mb4", $userName, $password, $options);
            return $pdo;
        } catch (PDOException $e) {
            throw new RuntimeException('DB connection failed: ' . $e->getMessage(), previous: $e);
        }
    }
}
