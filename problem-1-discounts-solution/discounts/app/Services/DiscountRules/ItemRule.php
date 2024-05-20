<?php declare(strict_types = 1);

namespace App\Services\DiscountRules;

use App\Helpers\OperatorsHelper;
use App\Services\DTO\ArrayOfItems;
use App\Services\DTO\Item;
use App\Services\DTO\Order;

class ItemRule implements ConditionInterface
{
    public function __construct(private readonly ArrayOfItems $items)
    {
    }

    public function evaluate($conditionKey, $operator, $targetValue): bool
    {
        /** @var Item $item */
        foreach ($this->items as $item) {
            $itemArray = $item->toArray();
            if (OperatorsHelper::evaluateCondition($itemArray[$conditionKey], $operator, $targetValue)) {
                return true;
            }
        }

        return false;
    }

}
