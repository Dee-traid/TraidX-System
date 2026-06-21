<?php

class Booking{
    private string $id;
    private string $userId;
    private string $flightId;
    private string $reference;
    private string $status;
    private float $totalAmount;
    private float $baseAmount;
    private float $taxAmount;
    private DateTimeImmutable $createdAt;
    private DateTimeImmutable $cancelledAt;
    private DateTimeImmutable $updatedAt;

    public function __construct(
        string $id, 
        string $userId, 
        string $flightId, 
        string $reference,
        string $status, 
        float $totalAmount, 
        float $baseAmount,
        float $taxAmount,
        DateTimeImmutable $createdAt, 
        DateTimeImmutable $cancelledAt,
        DateTimeImmutable $updatedAt
    ){
        $this->id = $id;
        $this->userId = $userId;
        $this->flightId = $flightId;
        $this->reference = $reference;
        $this->status = $status;
        $this->totalAmount = $totalAmount;
        $this->baseAmount = $baseAmount;
        $this->taxAmount = $taxAmount;
        $this->cancelledAt = $cancelledAt;
        $this->createdAt = $createdAt;
        $this->updatedAt = $updatedAt;
    }

    public function getId(): string{return $this->id;}
    public function getUserId(): string{return $this->userId;}
    public function getFlightId(): string{return $this->flightId;}
    public function getReference(): string{return $this->reference;}
    public function getStatus(): string{return $this->status;}
    public function getTotalAmount(): float{return $this->totalAmount;}
    public function getBaseAmount(): float{return $this->baseAmount;}
    public function getTaxAmount(): float{return $this->taxAmount;}
    public function getCreatedAt(): DateTimeImmutable{return $this->createdAt;}
    public function getCancelledAt(): DateTimeImmutable{return $this->cancelledAt;}
    public function getUpdatedAt(): DateTimeImmutable{return $this->updatedAt;}

    public function setId(string $id): void{$this->id = $id;}
    public function setUserId(string $userId): void{$this->userId = $userId;}
    public function setFlightId(string $flightId): void{$this->flightId = $flightId;}
    public function setReference(string $reference): void{$this->reference = $reference;}
    public function setStatus(string $status): void{$this->status = $status;}
    public function setTotalAmount(float $totalAmount): void{$this->totalAmount = $totalAmount;}
    public function setBaseAmount(float $baseAmount): void{$this->baseAmount = $baseAmount;}
    public function setTaxAmount(float $taxAmount): void{$this->taxAmount = $taxAmount;}
    public function setCreatedAt(DateTimeImmutable $createdAt): void{$this->createdAt = $createdAt;}
    public function setCancelledAt(DateTimeImmutable $cancelledAt): void{$this->cancelledAt = $cancelledAt;}
    public function setUpdatedAt(DateTimeImmutable $updatedAt): void{$this->updatedAt = $updatedAt;}


    public static function mapToBookingRow(array $data){
        $id = $data['id'];
        $userId = $data['user_id'];
        $flightId = $data['flight_id'];
        $reference = $data['reference'];
        $status = $data['status'];
        $totalAmount = (float)$data['total_amount'];
        $baseAmount = (float)$data['base_amount'];
        $taxAmount = (float)$data['tax_amount'];
        $createdAt = new DateTimeImmutable($data['created_at']);
        $cancelledAt = isset($data['cancelled_at']) ? new DateTimeImmutable($data['cancelled_at']) : null;
        $updatedAt = new DateTimeImmutable($data['updated_at']);

        return new Booking(
            $id, 
            $userId, 
            $flightId, 
            $reference, 
            $status, 
            $totalAmount, 
            $baseAmount, 
            $taxAmount, 
            $createdAt, 
            $cancelledAt, 
            $updatedAt
        );
    }
}