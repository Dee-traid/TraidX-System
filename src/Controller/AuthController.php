<?php

namespace App\Controller;

use App\Config\Database;
use App\Repositories\UserRepository;
use App\Services\AuthService;
use PDOException;

class AuthController
{
    public function registrationHandler()
    {
      try{
            
        header("Content-Type: application/json; charset=UTF-8");
        header("Access-Control-Allow-Methods: POST");
        header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

        $inputData = json_decode(file_get_contents("php://input"), true);// Data input from the user 

        $db = new Database();// a database connection object
        $dbConnection =  $db->getPDOInstance();

        $userRepo = new UserRepository($dbConnection);
        $authService = new AuthService($userRepo);

         $authService->register($inputData);

        http_response_code(201);
        echo json_encode([
            "status" => "success",
            "message" => "Account successfully created."
        ]);
        exit();
                
      }catch(PDOException $e){
        http_response_code(400); 
            echo json_encode([
                "status" => "error",
                "message" => $e->getMessage()
            ]);

      }
    }
}