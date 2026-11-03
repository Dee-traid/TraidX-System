<?php

namespace App\Services;

use App\Repositories\UserRepository;
use Exception;

class AuthService
{

    Private UserRepository $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }
    
    public  function register(array $data){

        $id = uniqid('user-', true);

        $firstName = trim($data['first_name'] ?? '');
        $lastName = trim($data['last_name'] ?? '');
        $email = trim($data['email'] ?? '');
        $phoneNumber = trim($data['phone'] ?? '');
        $Password = $data['password'] ?? '';
        $confirmPasswword = $data['confirm_password'];

        $userName = $firstName . "" . $lastName;

        $fields = ['$username', '$email', '$phone', '$password'];
        foreach($fields as $key => $field){
            if(empty($field)){
                throw new Exception(ucfirst(str_replace('_', ' ', $key)) . " is required.");
            }
        }  

        if (!preg_match('/^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{8,}$/', $Password)) {
            throw new Exception("Password must be at least 8 characters and contain both letters and numbers.");
             return;
        }

        if (!preg_match('/^(?:\+234|0)[789][01]\d{8}$/', $phoneNumber)) {
            throw new Exception("Please enter a valid phone number (e.g., 08012345678 or +2348012345678).");
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new Exception("Please enter a valid email address.");
        }
        
        $emailCheck = $this->userRepository->findByEmail($email) !== null;
        if ($emailCheck) {
            throw new Exception("Email already registered.");
        }

        if($Password !== $confirmPasswword){
             throw new Exception("Password does not match.");
        }      
        $password = password_hash($Password, PASSWORD_BCRYPT, ['cost' => 12]);

        $success = $this->userRepository->createUser($id, $userName, $email, $password, $phoneNumber);

        if (!$success) {
            throw new Exception("Critical error mapping registration properties to core data repository.");
        }

        return true;

        
			
    }
   
}