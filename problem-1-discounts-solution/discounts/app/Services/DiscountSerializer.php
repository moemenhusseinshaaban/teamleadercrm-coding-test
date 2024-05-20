<?php

namespace App\Services;

class DiscountSerializer
{
    const DISCOUNT = 'discount';
    const REASON = 'reason';

    public function __construct(
        private readonly string $discount,
        private readonly string $reason,
    ) {
    }

    public function serialize(): array
    {
        return [
            self::DISCOUNT => $this->discount,
            self::REASON => $this->reason
        ];
    }
}
