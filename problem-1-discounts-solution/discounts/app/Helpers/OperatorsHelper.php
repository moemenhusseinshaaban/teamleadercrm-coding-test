<?php declare(strict_types=1);

namespace App\Helpers;

use App\Enums\Operator;

class OperatorsHelper
{
    public static function evaluateCondition($conditionValue, Operator $operator, $targetValue): ?bool
    {
        return match ($operator) {
            Operator::EQUALS                => $conditionValue == $targetValue,
            Operator::NOT_EQUALS            => $conditionValue != $targetValue,
            Operator::GREATER_THAN          => $conditionValue > $targetValue,
            Operator::GREATER_THAN_OR_EQUAL => $conditionValue >= $targetValue,
            Operator::LESS_THAN             => $conditionValue < $targetValue,
            Operator::LESS_THAN_OR_EQUAL    => $conditionValue <= $targetValue,
        };
    }
}

