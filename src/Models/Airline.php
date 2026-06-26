<?php

class Airlines{
    private string $id;
    private string $name;
    private string $code;
    private ?string $logoUrl;
    private string $status;

    public function __construct(
        string $id, 
        string $name, 
        string $code, 
        ?string $logoUrl, 
        string $status
    ){
        $this->id = $id;
        $this->name = $name;
        $this->code = $code;
        $this->logoUrl = $logoUrl;
        $this->status = $status;
    }

    public function getId(): string{return $this->id;}
    public function getName(): string{return $this->name;}
    public function getCode(): string{return $this->code;}
    public function getLogoUrl(): ?string{return $this->logoUrl;}
    public function getStatus(): string{return $this->status;}

    public function setId(string $id): void{$this->id = $id;}
    public function setName(string $name): void{$this->name = $name;}
    public function setCode(string $code): void{$this->code = $code;}
    public function setLogoUrl(?string $logoUrl): void{$this->logoUrl = $logoUrl;}
    public function setStatus(string $status): void{$this->status = $status;}


    public static function mapToAirlineRow(array $data){
        $id = $data['id'];
        $name = $data['name'];
        $code = $data['code'];
        $logoUrl = $data['logo_url'] ?? null;
        $status = $data['status'];

        return new Airlines($id, $name, $code, $logoUrl, $status);
    }
    
}
