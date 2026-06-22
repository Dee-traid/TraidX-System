<?php 

class Payment{
    private string $id;
    private string $bookingId;
    private string $paymentMethod;
    private string $gateway;
    private string $gatewayTransactionId;
    private float $amount;
    private string $currency;
    private string $status;
    private DateTimeImmutable $createdAt;

    public function __construct(
        string $id, 
        string $bookingId, 
        string $paymentMethod, 
        string $gateway, 
        string $gatewayTransactionId, 
        float $amount, 
        string $currency, 
        string $status, 
        DateTimeImmutable $createdAt
    ){
        $this->id = $id;
        $this->bookingId = $bookingId;
        $this->paymentMethod = $paymentMethod;
        $this->gateway = $gateway;
        $this->gatewayTransactionId = $gatewayTransactionId;
        $this->amount = $amount;
        $this->currency = $currency;
        $this->status = $status;
        $this->createdAt = $createdAt;
    }

    public function getId(): string{return $this->id;}
    public function getBookingId(): string{return $this->bookingId;}
    public function getPaymentMethod(): string{return $this->paymentMethod;}
    public function getGateway(): string{return $this->gateway;}
    public function getGatewayTransactionId(): string{return $this->gatewayTransactionId;}
    public function getAmount(): float{return $this->amount;}
    public function getCurrency(): string{return $this->currency;}
    public function getStatus(): string{return $this->status;}
    public function getCreatedAt(): DateTimeImmutable{return $this->createdAt;}

    public function setId(string $id): void{$this->id = $id;}
    public function setBookingId(string $bookingId): void{$this->bookingId = $bookingId;}
    public function setPaymentMethod(string $paymentMethod): void{$this->paymentMethod = $paymentMethod;}
    public function setGateway(string $gateway): void{$this->gateway = $gateway;}
    public function setGatewayTransactionId(string $gatewayTransactionId): void{$this->gatewayTransactionId = $gatewayTransactionId;}
    public function setAmount(float $amount): void{$this->amount = $amount;}
    public function setCurrency(string $currency): void{$this->currency = $currency;}
    public function setStatus(string $status): void{$this->status = $status;}
    public function setCreatedAt(DateTimeImmutable $createdAt): void{$this->createdAt = $createdAt;}

    public static function mapToPaymentRow(array $data){
        $id = $data['id'];
        $bookingId = $data['booking_id'];
        $paymentMethod = $data['payment_method'];
        $gateway = $data['gateway'];
        $gatewayTransactionId = $data['gateway_transaction_id'];
        $amount = (float)$data['amount'];
        $currency = $data['currency'];
        $status = $data['status'];
        $createdAt = new DateTimeImmutable($data['created_at']);

        return new Payment($id, $bookingId, $paymentMethod, $gateway, $gatewayTransactionId, $amount, $currency, $status, $createdAt);
    }
}