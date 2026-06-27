<?php

namespace App\Service;

use App\Models\User;
use App\Controller\AuthController;
use App\Config\Database;
use PDO;
use PDOException;

class AuthService
{
    public static function register(){

        $json = file_get_contents('php:// inputs');
        $data = json_decode($json, true);
        
        $id = uniqid('user-');
        $userName


    }
   
}