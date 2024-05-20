<?php declare(strict_types = 1);

namespace App\Services\DiscountActions;

use App\Helpers\CalculationsHelper;
use App\Services\DiscountSerializer;
use App\Services\DTO\ArrayOfItems;

class PercentageOnCheapestBought implements ActionInterface
{
    const MESSAGE = '%s%% of discount on cheapest product (id: %s) it will be %s€ instead of %s€';

    public function __construct(
        private readonly ArrayOfItems $items,
        private readonly float $value,
        private readonly string $reason
    ) {
    }

    public function apply(): DiscountSerializer
    {
        $cheapestItem = CalculationsHelper::getCheapestItem($this->items);
        $cheapestUnitPrice = $cheapestItem->getUnitPrice();
        $totalAfterDiscount = CalculationsHelper::percentage($cheapestUnitPrice, $this->value);

        return new DiscountSerializer(
            sprintf(
                self::MESSAGE,
                $this->value,
                $cheapestItem->getProductId(),
                $totalAfterDiscount,
                $cheapestUnitPrice,
            ),
            $this->reason
        );
    }
}
