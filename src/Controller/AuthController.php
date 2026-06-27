<?php

namespace App\Controller;

use App\Service\AuthService;
use App\Models\User;
use App\Config\DatabaseConfig;
use PDO;
use Exception;
use App\Utils\Response;

class AuthController
{
    public static function register()
    {
        AuthService::register();
    }
}