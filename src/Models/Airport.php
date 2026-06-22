<?php

class Airport{
    private string $id;
    private string $name;
    private string $code;
    private string $city;
    private string $country;
    private DateTimeImmutable $timezone;
    
        public function __construct(
            string $id, 
            string $name, 
            string $code, 
            string $city, 
            string $country, 
            DateTimeImmutable $timezone
        ){
            $this->id = $id;
            $this->name = $name;
            $this->code = $code;
            $this->city = $city;
            $this->country = $country;
            $this->timezone = $timezone;
        }
    
        public function getId(): string{return $this->id;}
        public function getName(): string{return $this->name;}
        public function getCode(): string{return $this->code;}
        public function getCity(): string{return $this->city;}
        public function getCountry(): string{return $this->country;}
        public function getTimezone(): DateTimeImmutable{return $this->timezone;}

        public function setId(string $id): void{$this->id = $id;}
        public function setName(string $name): void{$this->name = $name;}
        public function setCode(string $code): void{$this->code = $code;}
        public function setCity(string $city): void{$this->city = $city;}
        public function setCountry(string $country): void{$this->country = $country;}
        public function setTimezone(DateTimeImmutable $timezone): void{$this->timezone = $timezone;}

        public static function mapToAirportRow(array $data){
            $id = $data['id'];
            $name = $data['name'];
            $code = $data['code'];
            $city = $data['city'];
            $country = $data['country'];
            $timezone = new DateTimeImmutable($data['timezone']);
    
            return new self($id, $name, $code, $city, $country, $timezone);
        }
}