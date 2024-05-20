<?php declare(strict_types = 1);

namespace App\Services\DiscountActions;

use App\Helpers\CalculationsHelper;
use App\Services\DiscountSerializer;
use App\Services\DTO\ArrayOfItems;
use App\Services\DTO\ArrayOfProducts;

class FreeProductsOfCategoryPerQuantity implements ActionInterface
{
    const MESSAGE = 'Earned %s free product(s) of category (id: %s)';

    public function __construct(
        private readonly ArrayOfItems $items,
        private readonly ArrayOfProducts $products,
        private readonly int $targetValue,
        private readonly int $actionValue,
        private readonly string $reason,
        private readonly string $categoryId,
    ) {
    }

    public function apply(): DiscountSerializer
    {
        $count = CalculationsHelper::countProductCategory($this->items, $this->products, $this->categoryId);

        $earnedCount = intdiv($count, $this->targetValue) * $this->actionValue;

        return new DiscountSerializer(
            sprintf(
                self::MESSAGE,
                $earnedCount,
                $this->categoryId
            ),
            $this->reason
        );
    }
}
