<?php declare(strict_types=1);

namespace Tests\Services\DiscountRules;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use App\Services\DiscountRules\CustomerRule;
use App\Services\DTO\Customer;
use App\Enums\Operator;

class CustomerRuleTest extends TestCase
{
    #[DataProvider('dataProvider')]
    public function testEvaluate(
        Customer $customer,
        string $conditionKey,
        Operator $operator,
        string $targetValue,
        bool $expectedResult
    ): void {
        $rule = new CustomerRule($customer);

        $result = $rule->evaluate($conditionKey, $operator, $targetValue);

        $this->assertEquals($expectedResult, $result);
    }

    public static function dataProvider(): array
    {
        return [
            'case 1: customer revenue equal to target value' => [
                'customer' => new Customer(id: '1', name: 'TeamLeader', since: '2015-01-15', revenue: 30),
                'conditionKey' => 'revenue',
                'operator' => Operator::EQUALS,
                'targetValue' => '30',
                'expectedResult' => true
            ],
            'case 2: customer revenue greater than target value' => [
                'customer' => new Customer(id: '1', name: 'TeamLeader', since: '2015-01-15', revenue: 35),
                'conditionKey' => 'revenue',
                'operator' => Operator::GREATER_THAN,
                'targetValue' => '30',
                'expectedResult' => true
            ],
            'case 3: customer revenue did not match condition' => [
                'customer' => new Customer(id: '1', name: 'TeamLeader', since: '2015-01-15', revenue: 20),
                'conditionKey' => 'revenue',
                'operator' => Operator::GREATER_THAN,
                'targetValue' => '30',
                'expectedResult' => false
            ]
        ];
    }
}
