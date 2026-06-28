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

        $userName = trim($data['username'] ?? '');
        $email = trim($data['email'] ?? '');
        $phone = trim($data['phone'] ?? '');
        $password = $data['password'] ?? '';

        $fields = ['$username', '$email', '$phone', '$password'];
        foreach($fields as $field){
            if(empty([$field])){
                return "This $field is required";

            }
        }

        if (!preg_match('/^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{8,}$/', $password)) {
                return "Password must be at least 8 characters and contain both letters and numbers.";
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return "Enter a valid email address.";
        }
            $emailCheck = $this->userRepository->findByEmail($email) !== null;
        if ($emailCheck) {
            throw new Exception("Email already registered.");
        }

        $id = uniqid('user-', true);
        
        // if ($data['password'] !== $data['confirmPassword']) {
        //         return "Passwords do not match.";
        // }

        $success = $this->userRepository->createUser($id, $userName, $email, $password);

        //$hashedPassword = password_hash($password, PASSWORD_BCRYPT, ['cost' => 12]);
			
    }
   
}