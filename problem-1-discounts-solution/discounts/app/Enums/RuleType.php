<?php declare(strict_types=1);

namespace App\Enums;

enum RuleType: string
{
    case ORDER = 'order';
    case CUSTOMER = 'customer';
    case PRODUCT = 'product';
}
