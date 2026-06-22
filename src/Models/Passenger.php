<?php

class Passenger{
    private string $id;
    private string $bookingId;
    private int $seatId;
    private string $userName;
    private DateTimeImmutable $dateOfBooking;
    private string $paassportNumber;
    private string $nationality;

    public function __construct(
        string $id, 
        string $bookingId, 
        int $seatId, 
        string $userName, 
        DateTimeImmutable $dateOfBooking, 
        string $paassportNumber, 
        string $nationality
    ){
        $this->id = $id;
        $this->bookingId = $bookingId;
        $this->seatId = $seatId;
        $this->userName = $userName;
        $this->dateOfBooking = $dateOfBooking;
        $this->paassportNumber = $paassportNumber;
        $this->nationality = $nationality;
    }
    public function getId(): string{return $this->id;}
    public function getBookingId(): string{return $this->bookingId;}
    public function getSeatId(): int{return $this->seatId;}
    public function getUserName(): string{return $this->userName;}
    public function getDateOfBooking(): DateTimeImmutable{return $this->dateOfBooking;}
    public function getPassportNumber(): string{return $this->paassportNumber;}
    public function getNationality(): string{return $this->nationality;}


    public function setId(string $id): void{$this->id = $id;}
    public function setBookingId(string $bookingId): void{$this->bookingId = $bookingId;}
    public function setSeatId(int $seatId): void{$this->seatId = $seatId;}
    public function setUserName(string $userName): void{$this->userName = $userName;}
    public function setDateOfBooking(DateTimeImmutable $dateOfBooking): void{$this->dateOfBooking = $dateOfBooking;}
    public function setPassportNumber(string $passportsNumber): void{$this->paassportNumber = $passportsNumber;}
    public function setNationality(string $nationality): void{$this->nationality = $nationality;}

    public static function mapToPassengerRow(array $data){
        $id = $data['id'];
        $bookingId = $data['booking_id'];
        $seatId = $data['seat_id'];
        $userName = $data['user_name'];
        $dateOfBooking = new DateTimeImmutable($data['date_of_booking']);
        $passportNumber = $data['passport_number'];
        $nationality = $data['nationality'];

        return new self($id, $bookingId, $seatId, $userName, $dateOfBooking, $passportNumber, $nationality);
    }
}