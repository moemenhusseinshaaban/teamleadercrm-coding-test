<?php declare(strict_types=1);

namespace Tests\Services\DiscountActions;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use App\Services\DiscountActions\PercentageOnCheapestBought;
use App\Services\DTO\ArrayOfItems;
use App\Services\DiscountSerializer;
use App\Services\DTO\Item;

class PercentageOnCheapestBoughtTest extends TestCase
{
    #[DataProvider('dataProvider')]
    public function testApply(
        ArrayOfItems $items,
        float $value,
        string $reason,
        string $expectedDiscount,
        string $expectedReason
    ): void {

        $discountAction = new PercentageOnCheapestBought(
            $items,
            $value,
            $reason
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
            'case 1: single item' => [
                'items' => new ArrayOfItems([new Item(productId: 'p1', quantity: 1, unitPrice: 10, total: 10)]),
                'value' => 10,
                'reason' => 'Test Reason 1',
                'expectedDiscount' => '10% of discount on cheapest product (id: p1) it will be 9€ instead of 10€',
                'expectedReason' => 'Test Reason 1'
            ],
            'case 2: multiple items' => [
                'items' => new ArrayOfItems([
                    new Item(productId: 'p1', quantity: 1, unitPrice: 10, total: 10),
                    new Item(productId: 'p2', quantity: 1, unitPrice: 5, total: 5),
                    new Item(productId: 'p3', quantity: 1, unitPrice: 15, total: 15),
                ]),
                'value' => 20.0,
                'reason' => 'Test Reason 2',
                'expectedDiscount' => '20% of discount on cheapest product (id: p2) it will be 4€ instead of 5€',
                'expectedReason' => 'Test Reason 2'
            ],
        ];
    }
}
