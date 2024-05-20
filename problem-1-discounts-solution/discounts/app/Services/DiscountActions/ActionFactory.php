<?php declare(strict_types = 1);

namespace App\Services\DiscountActions;

use App\Enums\RuleActionType;
use App\Models\Rule;
use App\Services\DTO\ArrayOfProducts;
use App\Services\DTO\Order;

class ActionFactory
{
    public static function createAction(
        Rule $rule,
        Order $order,
        ArrayOfProducts $products
    ): ?ActionInterface {

        return match ($rule->getActionType()) {
            RuleActionType::PERCENTAGE_ON_TOTAL_AMOUNT => new PercentageOnTotalAmount(
                $order->getTotal(),
                (float) $rule->getActionValue(),
                $rule->getReason()
            ),
            RuleActionType::PERCENTAGE_ON_CHEAPEST_BOUGHT => new PercentageOnCheapestBought(
                $order->getItems(),
                (float) $rule->getActionValue(),
                $rule->getReason()
            ),
            RuleActionType::FREE_PRODUCTS_OF_CATEGORY_PER_QUANTITY => new FreeProductsOfCategoryPerQuantity(
                $order->getItems(),
                $products,
                (int) $rule->getTargetValue(),
                (int) $rule->getActionValue(),
                $rule->getReason(),
                $rule->getActionKey()
            ),
        };
    }
}
