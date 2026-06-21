<?php
namespace Database;

use PDO;
use PDOException;

require_once __DIR__ . '/../config/database.php';

class Connection{
    private static $pdo = null;

    public static function getPDOInstance(){
        if(self::$pdo != null){
            return self::$pdo;
        }
        $config = require __DIR__ . '/../config/database.php';
        
        $host = $config['HOST'];
        $port = $config['PORT'];
        $database = $config['DATABASE'];
        $username = $config['USERNAME'];
        $password = $config['PASSWORD'];

        $dsn = "pgsql:host=$host;port=$port;dbname=$database";

        try{
            self::$pdo = new PDO($dsn, $username, $password);
            self::$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return self::$pdo;
        }catch(PDOException $e){
            http_response_code(500);
            header('Content-Type: application/json');
            die(json_encode([
                "status" => "error", 
                "message" => "Database connection failed " 
            ]));
            
        }

    }
}