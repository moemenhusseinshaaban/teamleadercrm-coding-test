<?php declare(strict_types = 1);

namespace App\Services\DiscountActions;

use App\Helpers\CalculationsHelper;
use App\Services\DiscountSerializer;

class PercentageOnTotalAmount implements ActionInterface
{
    const MESSAGE = 'Total after discount is: %s';

    public function __construct(
        private readonly float $total,
        private readonly float $value,
        private readonly string $reason
    ) {
    }

    public function apply(): DiscountSerializer
    {
        $totalAfterDiscount = CalculationsHelper::percentage($this->total, $this->value);

        return new DiscountSerializer(
            sprintf(self::MESSAGE, $totalAfterDiscount),
            $this->reason
        );
    }
}
