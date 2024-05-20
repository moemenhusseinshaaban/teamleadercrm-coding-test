<?php declare(strict_types = 1);

namespace App\Services\DiscountRules;

use App\Helpers\OperatorsHelper;
use App\Services\DTO\Order;

class OrderRule implements ConditionInterface
{
    public function __construct(private readonly Order $order)
    {
    }

    public function evaluate($conditionKey, $operator, $targetValue): bool
    {
        $order = $this->order->toArray();

        return OperatorsHelper::evaluateCondition($order[$conditionKey], $operator, $targetValue);
    }

}
