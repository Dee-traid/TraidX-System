<?php

class User{
    private string $id;
    private string $userName;
    private string $email;
    private string $password;
    private string $phoneNumber;
    private string $role;  
    private string $status;
    private DateTimeImmutable $createdAt;
    private DateTimeImmutable $updatedAt;

    public function __construct(
        string $id, 
        string $userName,
        string $email, 
        string $password, 
        string $phoneNumber, 
        string $role,
        string $status, 
        DateTimeImmutable $createdAt, 
        DateTimeImmutable $updatedAt
        ){

        $this->id = $id;
        $this->userName = $userName;
        $this->email = $email;
        $this->password = $password;
        $this->phoneNumber = $phoneNumber;
        $this->role = $role;
        $this->status = $status;
        $this->createdAt = $createdAt;
        $this->updatedAt = $updatedAt;
    }

    public function getId(): string{return $this->id;}
    public function getUserName(): string{return $this->userName;}
    public function getEmail(): string{return $this->email;}
    public function getPassword(): string{return $this->password;}
    public function getPhoneNumber(): string{return $this->phoneNumber;}
    public function getRole(): string{return $this->role;}
    public function getStatus(): string{return $this->status;}
    public function getCreatedAt(): DateTimeImmutable{return $this->createdAt;}
    public function getUpdatedAt(): DateTimeImmutable{return $this->updatedAt;}

    public function setId(string $id): void{$this->id = $id;}
    public function setUserName(string $userName): void{$this->userName = $userName;}
    public function setEmail(string $email): void{$this->email = $email;}
    public function setPassword(string $password): void{$this->password = $password;}
    public function setPhoneNumber(string $phoneNumber): void{$this->phoneNumber = $phoneNumber;}
    public function setRole(string $role): void{$this->role = $role;}
    public function setStatus(string $status): void{$this->status = $status;}
    public function setCreatedAt(DateTimeImmutable $createdAt): void{$this->createdAt = $createdAt;}
    public function setUpdatedAt(DateTimeImmutable $updatedAt): void{$this->updatedAt = $updatedAt;}

    public static function mapToUserRow(array $data){
            $id = $data['id'];
            $userName = $data['user_name'];
            $email = $data['email'];
            $password = $data['password'];
            $phoneNumber = $data['phone_number'];
            $role = $data['role'];
            $status = $data['status'];
            $createdAt = new DateTimeImmutable($data['created_at']);
            $updatedAt = new DateTimeImmutable($data['updated_at']);
            
        return new User($id, $userName, $email, $password, $phoneNumber, $role, $status, $createdAt, $updatedAt);
    }

}