<?php

declare(strict_types=1);

namespace Domain;

class City
{
    private string $id;
    private string $name;
    private string $postalCode;

    public function __construct(string $name, string $postalCode)
    {
        $this->id = uniqid('city_', true);
        $this->name = $name;
        $this->postalCode = $postalCode;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getPostalCode(): string
    {
        return $this->postalCode;
    }

    public function setPostalCode(string $postalCode): void
    {
        $this->postalCode = $postalCode;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'postalCode' => $this->postalCode,
        ];
    }
}