<?php declare(strict_types=1);

namespace App\Enums;

enum RuleSubtype: string
{
    case CUSTOMER = 'customer';
    case ORDER = 'order';
    case ITEM = 'item';
    case CATEGORY_QUANTITY = 'category_quantity';
}
