<?php declare(strict_types = 1);

namespace App\Services\DiscountRules;

use App\Enums\RuleSubtype;
use App\Services\DTO\ArrayOfProducts;
use App\Services\DTO\Customer;
use App\Services\DTO\Order;

class RuleFactory
{
    public static function createRule(
        RuleSubtype $type,
        ?Customer $customer,
        Order $order,
        ArrayOfProducts $products
    ): ?ConditionInterface {

        return match ($type) {
            RuleSubtype::CUSTOMER => new CustomerRule($customer),
            RuleSubtype::ORDER => new OrderRule($order),
            RuleSubtype::ITEM => new ItemRule($order->getItems()),
            RuleSubtype::CATEGORY_QUANTITY => new CategoryQuantityRule($order->getItems(), $products),
        };
    }
}
