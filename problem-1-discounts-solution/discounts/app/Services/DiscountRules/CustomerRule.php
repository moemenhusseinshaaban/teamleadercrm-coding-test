<?php declare(strict_types = 1);

namespace App\Services\DiscountRules;

use App\Helpers\OperatorsHelper;
use App\Services\DTO\Customer;

class CustomerRule implements ConditionInterface
{
    public function __construct(private readonly Customer $customer)
    {
    }

    public function evaluate($conditionKey, $operator, $targetValue): bool
    {
        $customer = $this->customer->toArray();

        return OperatorsHelper::evaluateCondition($customer[$conditionKey], $operator, $targetValue);
    }
}
