<?php

class Notification{
    private string $id;
    private string $userId;
    private int $bookingId;
    private string $type;
    private string $channel;
    private string $title;
    private string $message;
    private string $isRead;
    private DateTimeImmutable $sentAt;

    public function __construct(
        string $id, 
        string $userId, 
        int $bookingId, 
        string $type, 
        string $channel, 
        string $title, 
        string $message, 
        string $isRead, 
        DateTimeImmutable $sentAt
    ){
        $this->id = $id;
        $this->userId = $userId;
        $this->bookingId = $bookingId;
        $this->type = $type;
        $this->channel = $channel;
        $this->title = $title;
        $this->message = $message;
        $this->isRead = $isRead;
        $this->sentAt = $sentAt;
    }

    public function getId(): string{return $this->id;}
    public function getUserId(): string{return $this->userId;}
    public function getBookingId(): int{return $this->bookingId;}
    public function getType(): string{return $this->type;}
    public function getChannel(): string{return $this->channel;}
    public function getTitle(): string{return $this->title;}
    public function getMessage(): string{return $this->message;}
    public function getIsRead(): string{return $this->isRead;}
    public function getSentAt(): DateTimeImmutable{return $this->sentAt;}

    public function setId(string $id): void{$this->id = $id;}
    public function setUserId(string $userId): void{$this->userId = $userId;}
    public function setBookingId(int $bookingId): void{$this->bookingId = $bookingId;}
    public function setType(string $type): void{$this->type = $type;}
    public function setChannel(string $channel): void{$this->channel = $channel;}
    public function setTitle(string $title): void{$this->title = $title;}
    public function setMessage(string $message): void{$this->message = $message;}
    public function setIsRead(string $isRead): void{$this->isRead = $isRead;}
    public function setSentAt(DateTimeImmutable $sentAt): void{$this->sentAt = $sentAt;}

    public static function mapToNotificationRow(array $data){
        $id = $data['id'];
        $userId = $data['user_id'];
        $bookingId = $data['booking_id'];
        $type = $data['type'];
        $channel = $data['channel'];
        $title = $data['title'];
        $message = $data['message'];
        $isRead = $data['is_read'];
        $sentAt = new DateTimeImmutable($data['sent_at']);

        return new self($id, $userId, $bookingId, $type, $channel, $title, $message, $isRead, $sentAt);
    }

}