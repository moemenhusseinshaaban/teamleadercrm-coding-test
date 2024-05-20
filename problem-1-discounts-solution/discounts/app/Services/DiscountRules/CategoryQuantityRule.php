<?php declare(strict_types = 1);

namespace App\Services\DiscountRules;

use App\Enums\Operator;
use App\Helpers\CalculationsHelper;
use App\Helpers\OperatorsHelper;
use App\Services\DTO\ArrayOfItems;
use App\Services\DTO\ArrayOfProducts;

class CategoryQuantityRule implements ConditionInterface
{
    public function __construct(private readonly ArrayOfItems $items, private readonly ArrayOfProducts $products)
    {
    }

    public function evaluate(string $conditionKey, Operator $operator, string $targetValue): bool
    {
        $count = CalculationsHelper::countProductCategory($this->items, $this->products, $conditionKey);

        return OperatorsHelper::evaluateCondition($count, $operator, $targetValue);
    }

}
