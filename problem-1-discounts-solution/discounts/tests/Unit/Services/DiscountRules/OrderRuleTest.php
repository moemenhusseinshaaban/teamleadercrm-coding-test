<?php declare(strict_types=1);

namespace Tests\Services\DiscountRules;

use App\Services\DTO\ArrayOfItems;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use App\Services\DiscountRules\OrderRule;
use App\Services\DTO\Order;
use App\Enums\Operator;

class OrderRuleTest extends TestCase
{
    #[DataProvider('dataProvider')]
    public function testEvaluate(
        Order $order,
        string $conditionKey,
        Operator $operator,
        string $targetValue,
        bool $expectedResult
    ): void {
        $rule = new OrderRule($order);

        $result = $rule->evaluate($conditionKey, $operator, $targetValue);

        $this->assertEquals($expectedResult, $result);
    }

    public static function dataProvider(): array
    {
        return [
            'case 1: total equal to target value' => [
                'order' => new Order(id: '1', customerId: '1', items: new ArrayOfItems(), total: 50),
                'conditionKey' => 'total',
                'operator' => Operator::EQUALS,
                'targetValue' => '50',
                'expectedResult' => true
            ],
            'case 2: invalid total not equal to target value' => [
                'order' => new Order(id: '1', customerId: '1', items: new ArrayOfItems(), total: 50),
                'conditionKey' => 'total',
                'operator' => Operator::EQUALS,
                'targetValue' => '10',
                'expectedResult' => false
            ],
        ];
    }
}
