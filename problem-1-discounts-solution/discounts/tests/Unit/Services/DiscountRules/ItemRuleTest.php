<?php declare(strict_types=1);

namespace Tests\Services\DiscountRules;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use App\Services\DiscountRules\ItemRule;
use App\Services\DTO\ArrayOfItems;
use App\Services\DTO\Item;
use App\Enums\Operator;

class ItemRuleTest extends TestCase
{
    #[DataProvider('dataProvider')]
    public function testEvaluate(
        ArrayOfItems $items,
        string $conditionKey,
        Operator $operator,
        string $targetValue,
        bool $expectedResult
    ): void {

        $rule = new ItemRule($items);

        $result = $rule->evaluate($conditionKey, $operator, $targetValue);

        $this->assertEquals($expectedResult, $result);
    }

    public static function dataProvider(): array
    {
        return [
            'case 1: quantity equal to target value' => [
                'items' => new ArrayOfItems([
                    new Item(productId: 'p1', quantity: 10, unitPrice: 5, total: 50),
                    new Item(productId: 'p2', quantity: 5, unitPrice: 2, total: 10)
                ]),
                'conditionKey' => 'quantity',
                'operator' => Operator::EQUALS,
                'targetValue' => '10',
                'expectedResult' => true
            ],
            'case 2: unit-price greater than target value' => [
                'items' => new ArrayOfItems([
                    new Item(productId: 'p1', quantity: 10, unitPrice: 5, total: 50),
                    new Item(productId: 'p2', quantity: 5, unitPrice: 2, total: 10)
                ]),
                'conditionKey' => 'unit-price',
                'operator' => Operator::GREATER_THAN,
                'targetValue' => '4',
                'expectedResult' => true
            ],
            'case 3: invalid unit-price condition not greater than target value' => [
                'items' => new ArrayOfItems([
                    new Item(productId: 'p1', quantity: 10, unitPrice: 5, total: 50),
                    new Item(productId: 'p2', quantity: 5, unitPrice: 2, total: 10)
                ]),
                'conditionKey' => 'unit-price',
                'operator' => Operator::GREATER_THAN,
                'targetValue' => '20',
                'expectedResult' => false
            ]
        ];
    }
}
