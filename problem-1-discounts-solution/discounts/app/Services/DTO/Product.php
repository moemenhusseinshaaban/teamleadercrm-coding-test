<?php declare(strict_types = 1);

namespace App\Services\DTO;

class Product {

    public function __construct(
        private readonly string $id,
        private readonly string $description,
        private readonly string $category,
        private readonly float  $price
    ) {
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getCategory(): string
    {
        return $this->category;
    }

    public function getPrice(): float
    {
        return $this->price;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->getId(),
            'description' => $this->getDescription(),
            'category' => $this->getCategory(),
            'price' => $this->getPrice(),
        ];
    }
}
