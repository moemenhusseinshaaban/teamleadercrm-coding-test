<?php declare(strict_types = 1);

namespace App\Services\DTO;

class Item
{
    public function __construct(
        private readonly string $productId,
        private readonly int    $quantity,
        private readonly float  $unitPrice,
        private readonly float  $total
    ) {
    }

    public function getProductId(): string
    {
        return $this->productId;
    }

    public function getQuantity(): int
    {
        return $this->quantity;
    }

    public function getUnitPrice(): float
    {
        return $this->unitPrice;
    }

    public function getTotal(): float
    {
        return $this->total;
    }

    public function toArray(): array
    {
        return [
            'product-id' => $this->getProductId(),
            'quantity' => $this->getQuantity(),
            'unit-price' => $this->getUnitPrice(),
            'total' => $this->getTotal(),
        ];
    }
}
