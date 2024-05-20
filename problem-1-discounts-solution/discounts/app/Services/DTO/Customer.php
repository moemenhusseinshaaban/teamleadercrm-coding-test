<?php declare(strict_types = 1);

namespace App\Services\DTO;

class Customer
{
    public function __construct(
        private readonly string $id,
        private readonly string $name,
        private readonly string $since,
        private readonly float $revenue
    ) {
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getSince(): string
    {
        return $this->since;
    }

    public function getRevenue(): float
    {
        return $this->revenue;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->getId(),
            'name' => $this->getName(),
            'since' => $this->getSince(),
            'revenue' => $this->getRevenue(),
        ];
    }
}
