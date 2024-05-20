<?php declare(strict_types=1);

namespace Tests\Services\DiscountActions;

use App\Services\DTO\Item;
use App\Services\DTO\Product;
use App\Services\DiscountActions\FreeProductsOfCategoryPerQuantity;
use App\Services\DTO\ArrayOfItems;
use App\Services\DTO\ArrayOfProducts;
use App\Services\DiscountSerializer;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class FreeProductsOfCategoryPerQuantityTest extends TestCase
{
    #[DataProvider('dataProvider')]
    public function testApply(
        ArrayOfItems $items,
        ArrayOfProducts $products,
        int $targetValue,
        int $actionValue,
        string $reason,
        string $categoryId,
        string $expectedDiscount,
        string $expectedReason
    ): void {

        $discountAction = new FreeProductsOfCategoryPerQuantity(
            $items,
            $products,
            $targetValue,
            $actionValue,
            $reason,
            $categoryId
        );

        $discount = $discountAction->apply();

        $serializedDiscount = $discount->serialize();

        $this->assertInstanceOf(DiscountSerializer::class, $discount);
        $this->assertEquals($expectedDiscount, $serializedDiscount[DiscountSerializer::DISCOUNT]);
        $this->assertEquals($expectedReason, $serializedDiscount[DiscountSerializer::REASON]);
    }

    public static function dataProvider(): array
    {
        return [
            'case 1: products for one discount' => [
                'items' => new ArrayOfItems([new Item(productId: 'p1', quantity: 5, unitPrice: 1, total: 5)]),
                'products' => new ArrayOfProducts(['p1' => new Product(id: 'p1', description: '', category: 'cat1', price: 5)]),
                'targetValue' => 3,
                'actionValue' => 1,
                'reason' => 'Test Reason',
                'categoryId' => 'cat1',
                'expectedDiscount' => 'Earned 1 free product(s) of category (id: cat1)',
                'expectedReason' => 'Test Reason'
            ],
            'case 2: products for multiple discounts' => [
                'items' => new ArrayOfItems([new Item(productId: 'p1', quantity: 10, unitPrice: 1, total: 5)]),
                'products' => new ArrayOfProducts(['p1' => new Product(id: 'p1', description: '', category: 'cat1', price: 5)]),
                'targetValue' => 5,
                'actionValue' => 1,
                'reason' => 'Test Reason',
                'categoryId' => 'cat1',
                'expectedDiscount' => 'Earned 2 free product(s) of category (id: cat1)',
                'expectedReason' => 'Test Reason'
            ]
        ];
    }
}
