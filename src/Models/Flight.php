<?php

class Flight{
    private string $id;
    private string $airlineId;
    private string $flightNumber;
    private string $originId;
    private string $destinationId;
    private DateTimeImmutable $departureTime;
    private DateTimeImmutable $arrivalTime;
    private float $baseFare;
    private float $taxeRate;
    private float $totalSeats;
    private float $availableSeats;
    private string $status;
    private DateTimeImmutable $createdAt;

    public function __construct(
        string $id, 
        string $airlineId, 
        string $flightNumber, 
        string $originId, 
        string $destinationId, 
        DateTimeImmutable $departureTime, 
        DateTimeImmutable $arrivalTime, 
        float $baseFare, 
        float $taxeRate, 
        float $totalSeats, 
        float $availableSeats, 
        string $status, 
        DateTimeImmutable $createdAt
    ){
        $this->id = $id;
        $this->airlineId = $airlineId;
        $this->flightNumber = $flightNumber;
        $this->originId = $originId;
        $this->destinationId = $destinationId;
        $this->departureTime = $departureTime;
        $this->arrivalTime = $arrivalTime;
        $this->baseFare = $baseFare;
        $this->taxeRate = $taxeRate;
        $this->totalSeats = $totalSeats;
        $this->availableSeats = $availableSeats;
        $this->status = $status;
        $this->createdAt = $createdAt;
    }

    public function getId(): string{return $this->id;}
    public function getAirlineId(): string{return $this->airlineId;}
    public function getFlightNumber(): string{return $this->flightNumber;}
    public function getOriginId(): string{return $this->originId;}
    public function getDestinationId(): string{return $this->destinationId;}
    public function getDepartureTime(): DateTimeImmutable{return $this->departureTime;}
    public function getArrivalTime(): DateTimeImmutable{return $this->arrivalTime;}
    public function getBaseFare(): float{return $this->baseFare;}
    public function getTaxeRate(): float{return $this->taxeRate;}
    public function getTotalSeats(): float{return $this->totalSeats;}
    public function getAvailableSeats(): float{return $this->availableSeats;}
    public function getStatus(): string{return $this->status;}
    public function getCreatedAt(): DateTimeImmutable{return $this->createdAt;}

    public function setId(string $id): void{$this->id = $id;}
    public function setAirlineId(string $airlineId): void{$this->airlineId = $airlineId;}
    public function setFlightNumber(string $flightNumber): void{$this->flightNumber = $flightNumber;}
    public function setOriginId(string $originId): void{$this->originId = $originId;}
    public function setDestinationId(string $destinationId): void{$this->destinationId = $destinationId;}
    public function setDepartureTime(DateTimeImmutable $departureTime): void{$this->departureTime = $departureTime;}
    public function setArrivalTime(DateTimeImmutable $arrivalTime): void{$this->arrivalTime = $arrivalTime;}
    public function setBaseFare(float $baseFare): void{$this->baseFare = $baseFare;}
    public function setTaxeRate(float $taxeRate): void{$this->taxeRate = $taxeRate;}
    public function setTotalSeats(float $totalSeats): void{$this->totalSeats = $totalSeats;}
    public function setAvailableSeats(float $availableSeats): void{$this->availableSeats = $availableSeats;}
    public function setStatus(string $status): void{$this->status = $status;}
    public function setCreatedAt(DateTimeImmutable $createdAt): void{$this->createdAt = $createdAt;}

    public static function mapToFlightRow(array $data){
        $id = $data['id'];
        $airlineId = $data['airline_id'];
        $flightNumber = $data['flight_number'];
        $originId = $data['origin_id'];
        $destinationId = $data['destination_id'];
        $departureTime = new DateTimeImmutable($data['departure_time']);
        $arrivalTime = new DateTimeImmutable($data['arrival_time']);
        $baseFare = (float)$data['base_fare'];
        $taxeRate = (float)$data['taxe_rate'];
        $totalSeats = (float)$data['total_seats'];
        $availableSeats = (float)$data['available_seats'];
        $status = $data['status'];
        $createdAt = new DateTimeImmutable($data['created_at']);

        return new Flight(
            $id, 
            $airlineId, 
            $flightNumber, 
            $originId, 
            $destinationId, 
            $departureTime, 
            $arrivalTime, 
            $baseFare, 
            $taxeRate, 
            $totalSeats, 
            $availableSeats, 
            $status, 
            $createdAt
        );
    }
}