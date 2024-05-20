<?php declare(strict_types = 1);

namespace App\Services\DiscountRules;

use App\Enums\Operator;
use App\Enums\RuleSubtype;

interface ConditionInterface
{
    public function evaluate(string $conditionKey, Operator $operator, string $targetValue): bool;
}
