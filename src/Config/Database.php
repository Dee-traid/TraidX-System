<?php

require_once __DIR__ . '/../vendor/autoload.php';

if(file_exists(__DIR__ . '/../.env')){
    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
    $dotenv->load();
}

class Database
{
    private static $pdo = null;

    public static function getPDOInstance()
    {
        if(self::$pdo !== null){
           return self::$pdo;
        }

        $driver = getenv('DB_DRIVER');
        $host = getenv('DB_HOST');
        $port = getenv('DB_PORT');
        $database = getenv('DB_NAME');
        $username = getenv('DB_USER');
        $password = getenv('DB_PASS');

        $dsn = "pgsql:host=$host;port=$port;dbname=$database";
        try {
           self::$pdo =  new PDO($dsn, $username, $password);
           self::$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            http_response_code(500);
            header('content-type: application/json');

            die(json_encode([
                "status" => "error",
                "message" => "Database connection failed: " . $e->getMessage()
            ]));
        }
    }
}