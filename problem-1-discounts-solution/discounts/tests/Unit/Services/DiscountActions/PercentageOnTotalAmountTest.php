<?php declare(strict_types=1);

namespace Tests\Services\DiscountActions;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use App\Services\DiscountActions\PercentageOnTotalAmount;
use App\Services\DiscountSerializer;
use App\Helpers\CalculationsHelper;

class PercentageOnTotalAmountTest extends TestCase
{
    #[DataProvider('dataProvider')]
    public function testApply(
        float $total,
        float $value,
        string $reason,
        string $expectedDiscount,
        string $expectedReason
    ): void {
        $discountAction = new PercentageOnTotalAmount(
            $total,
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
            'case 1: 10 percent discount on 100 total' => [
                'total' => 100,
                'value' => 10,
                'reason' => 'Test Reason 1',
                'expectedDiscount' => 'Total after discount is: 90',
                'expectedReason' => 'Test Reason 1'
            ],
            'case 2: 10 percent discount on 100 total' => [
                'total' => 100,
                'value' => 20,
                'reason' => 'Test Reason 1',
                'expectedDiscount' => 'Total after discount is: 80',
                'expectedReason' => 'Test Reason 1'
            ],
        ];
    }
}
