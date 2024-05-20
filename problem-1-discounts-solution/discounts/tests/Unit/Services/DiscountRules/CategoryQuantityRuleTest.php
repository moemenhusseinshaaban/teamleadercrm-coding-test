<?php declare(strict_types=1);

namespace Tests\Services\DiscountRules;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use App\Services\DiscountRules\CategoryQuantityRule;
use App\Services\DTO\ArrayOfItems;
use App\Services\DTO\ArrayOfProducts;
use App\Enums\Operator;
use App\Services\DTO\Item;
use App\Services\DTO\Product;

class CategoryQuantityRuleTest extends TestCase
{
    #[DataProvider('dataProvider')]
    public function testEvaluate(
        ArrayOfItems $items,
        ArrayOfProducts $products,
        string $conditionKey,
        Operator $operator,
        string $targetValue,
        bool $expectedResult
    ): void {

        $rule = new CategoryQuantityRule($items, $products);

        $result = $rule->evaluate($conditionKey, $operator, $targetValue);

        $this->assertEquals($expectedResult, $result);
    }

    public static function dataProvider(): array
    {
        return [
            'case 1: no items in required category' => [
                'items' => new ArrayOfItems([new Item(productId: 'p1', quantity: 3, unitPrice: 10, total: 30)]),
                'products' => new ArrayOfProducts([
                    'p1' => new Product(id: 'p1', description: '', category: 'cat2', price: 10),
                    'p2' => new Product(id: 'p2', description: '', category: 'cat1', price: 10)
                ]),
                'conditionKey' => 'cat1',
                'operator' => Operator::EQUALS,
                'targetValue' => '2',
                'expectedResult' => false
            ],
            'case 2: products in category, operator greater than, target 2' => [
                'items' => new ArrayOfItems([new Item(productId: 'p1', quantity: 3, unitPrice: 10, total: 30)]),
                'products' => new ArrayOfProducts(['p1' => new Product(id: 'p1', description: '', category: 'cat1', price: 10)]),
                'conditionKey' => 'cat1',
                'operator' => Operator::GREATER_THAN,
                'targetValue' => '2',
                'expectedResult' => true
            ],
            'case 3: products in category, operator less than, target 4' => [
                'items' => new ArrayOfItems([new Item(productId: 'p1', quantity: 3, unitPrice: 10, total: 30)]),
                'products' => new ArrayOfProducts(['p1' => new Product(id: 'p1', description: '', category: 'cat1', price: 10)]),
                'conditionKey' => 'cat1',
                'operator' => Operator::LESS_THAN,
                'targetValue' => '4',
                'expectedResult' => true
            ],
            'case 4: products in category, operator equal, target 3' => [
                'items' => new ArrayOfItems([new Item(productId: 'p1', quantity: 3, unitPrice: 10, total: 30)]),
                'products' => new ArrayOfProducts(['p1' => new Product(id: 'p1', description: '', category: 'cat1', price: 10)]),
                'conditionKey' => 'cat1',
                'operator' => Operator::EQUALS,
                'targetValue' => '3',
                'expectedResult' => true
            ],
        ];
    }
}
