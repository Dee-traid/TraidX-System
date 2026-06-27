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

    public function findOneByEmail(string $email) : string
    {
        $query = "SELECT email FROM users WHERE email = :email LIMIT 1";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':email', $email);
        $stmt->execute();

        $user = $stmt->fetch();
        return $user ? $user['email'] : null;
    }

    public function createUser()
    {
        $query = "INSERT INTO users (id, email, password, phone, role, status, created_at, updated_at) 
                        VALUES (:id, :email, :password, :phone, :role, :status, :createdAt, :updatedAt)";
        
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
    }
}