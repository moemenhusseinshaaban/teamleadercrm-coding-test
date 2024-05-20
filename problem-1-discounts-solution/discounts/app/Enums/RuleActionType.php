<?php declare(strict_types=1);

namespace App\Enums;

enum RuleActionType: string
{
    case PERCENTAGE_ON_TOTAL_AMOUNT = 'percentage_on_total_amount';
    case PERCENTAGE_ON_CHEAPEST_BOUGHT = 'percentage_on_cheapest_bought';
    case FREE_PRODUCTS_OF_CATEGORY_PER_QUANTITY = 'free_products_of_category_per_quantity';
}
