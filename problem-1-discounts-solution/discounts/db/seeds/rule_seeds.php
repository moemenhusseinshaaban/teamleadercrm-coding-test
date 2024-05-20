<?php
use App\Enums\RuleType;
use App\Enums\RuleSubtype;
use App\Enums\Operator;
use App\Enums\RuleActionType;

return [
    [
        'type' => RuleType::CUSTOMER->value,
        'subtype' => RuleSubtype::CUSTOMER->value,
        'condition_key' => 'revenue', // customerData
        'operator' => Operator::GREATER_THAN->value,
        'target_value' => '1000',
        'action_type' => RuleActionType::PERCENTAGE_ON_TOTAL_AMOUNT->value,
        'action_value' => '10',
        'reason' => 'A customer who has already bought for over € 1000, gets a discount of 10% on the whole order.',
        'action_key' => null,
    ],
    [
        'type' => RuleType::PRODUCT->value,
        'subtype' => RuleSubtype::CATEGORY_QUANTITY->value,
        'condition_key' => '2', // categoryID
        'operator' => Operator::GREATER_THAN_OR_EQUAL->value,
        'target_value' => '5',
        'action_type' => RuleActionType::FREE_PRODUCTS_OF_CATEGORY_PER_QUANTITY->value,
        'action_value' => '1',
        'reason' => 'For every product of category "Switches" (id 2), when you buy five, you get a sixth for free',
        'action_key' => '2', // categoryId of the free product
    ],
    [
        'type' => RuleType::PRODUCT->value,
        'subtype' => RuleSubtype::CATEGORY_QUANTITY->value,
        'condition_key' => '1', // categoryID
        'operator' => Operator::GREATER_THAN_OR_EQUAL->value,
        'target_value' => '2',
        'action_type' => RuleActionType::PERCENTAGE_ON_CHEAPEST_BOUGHT->value,
        'action_value' => '20',
        'reason' => 'If you buy two or more products of category "Tools" (id 1), you get a 20% discount on the cheapest product.',
        'action_key' => null,
    ],
];
