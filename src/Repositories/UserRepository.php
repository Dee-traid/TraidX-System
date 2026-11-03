<?php

namespace App\Repositories;

use PDO;
use PDOException;
use App\Models\User;
class UserRepository{

    private PDO $db;

    public function __construct(PDO $db){
        $this->db = $db;
    }

    public function findByEmail(string $email)
    {
        try{
            $query = "SELECT email FROM users WHERE email = :email LIMIT 1";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':email', $email);
            $stmt->execute();

            $user = $stmt->fetch();
            if($email){
                return $user ? $user['email'] : null;
            }      

        }catch(PDOException $e){
            // throw new PDOException("Email not found $e->getMessage()");
        }
       
    }

     public function findByID(string $id)
    {
        $query = "SELECT id FROM users WHERE id = :id LIMIT 1";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();

        $user = $stmt->fetch();
         if($id){
            return $user ? $user['id'] : null;
        }
        
    }

    public function createUser(string $id, string $name, string $email, string $password, string $phone)
    {
        $query = "INSERT INTO users (id, email, password, phone, role, status, created_at, updated_at) 
                        VALUES (:id, :email, :password, :phone, :role, :status, :createdAt, :updatedAt)";
        $role = 'customer';
        $status = 'active';
        $createdAt = date('Y-m-d H:i:s');
        $updatedAt = date('Y-m-d H:i:s');


        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $password);
        $stmt->bindParam(':phone', $phone);
        $stmt->bindParam(':role', $role);
        $stmt->bindParam(':status', $status);
        $stmt->bindParam(':createdAt', $createdAt);
        $stmt->bindParam(':updatedAt', $updatedAt);
        $stmt->execute();

        return true;
    }

    public function rateCheck(){
        
    }
}