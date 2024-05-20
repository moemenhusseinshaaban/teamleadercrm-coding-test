<?php declare(strict_types = 1);

namespace App\Services\DTO;

class Order
{
    public function __construct(
        private readonly string $id,
        private readonly string $customerId,
        private readonly ArrayOfItems $items,
        private readonly float        $total
    ) {
    }

    public function getId(): string {
        return $this->id;
    }

    public function getCustomerId(): string
    {
        return $this->customerId;
    }

    public function getItems(): ArrayOfItems
    {
        return $this->items;
    }

    public function getTotal(): float
    {
        return $this->total;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->getId(),
            'customer-id' => $this->getCustomerId(),
            'items' => $this->getItems(),
            'total' => $this->getTotal(),
        ];
    }
}
