<?php declare(strict_types = 1);

namespace Tests\Functional;

use App\Services\DiscountActions\FreeProductsOfCategoryPerQuantity;
use App\Services\DiscountActions\PercentageOnCheapestBought;
use App\Services\DiscountActions\PercentageOnTotalAmount;
use App\Services\DiscountSerializer;
use App\Services\ExternalAPI\ExternalAPI;
use DI\Container;

class DiscountsControllerFunctionalTest extends FunctionalBasedTest
{
    private ExternalAPI $externalAPI;

    protected function setUp(): void
    {
        parent::setUp();

        $this->externalAPI = $this->getMockBuilder(ExternalAPI::class)
            ->onlyMethods(['getCustomerById', 'getProducts'])
            ->disableOriginalConstructor()
            ->getMock();

        /** @var Container $container */
        $container = $this->app->getContainer();
        $container->set(ExternalAPI::class, $this->externalAPI);
    }

    public function testOnlyTotalAmountCustomerBasedRuleDiscountsRequest(): void
    {
        $this->externalAPI->method('getCustomerById')->willReturn(['id' => '1', 'name' => 'Teamleader', 'since' => '2015-01-15', 'revenue' => '1505.95']);
        $this->externalAPI->method('getProducts')->willReturn($this->productsData());

        $requestBody = [
            'id' => '3',
            'customer-id' => '2',
            'items' => [
                ['product-id' => 'A101', 'quantity' => '1', 'unit-price' => '100', 'total' => '100'],
            ],
            'total' => '100'
        ];

        $request = $this->createRequest('POST', '/discounts', $requestBody);
        $response = $this->app->handle($request);
        $expectedDiscounts = [
            (new DiscountSerializer(
                sprintf(PercentageOnTotalAmount::MESSAGE, '90'),
                'A customer who has already bought for over € 1000, gets a discount of 10% on the whole order.'
            ))->serialize()
        ];

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertJsonStringEqualsJsonString(json_encode($expectedDiscounts), (string) $response->getBody());
    }

    public function testProductBasedRuleDiscountsRequest(): void
    {
        $this->externalAPI->method('getCustomerById')->willReturn([]);
        $this->externalAPI->method('getProducts')->willReturn($this->productsData());

        $requestBody = [
            'id' => '3',
            'customer-id' => '1',
            'items' => [
                ['product-id' => 'A101', 'quantity' => '2', 'unit-price' => '100', 'total' => '200'],
                ['product-id' => 'B101', 'quantity' => '5', 'unit-price' => '100', 'total' => '500'],
                ['product-id' => 'B102', 'quantity' => '5', 'unit-price' => '10', 'total' => '50'],
            ],
            'total' => '430'
        ];

        $request = $this->createRequest('POST', '/discounts', $requestBody);
        $response = $this->app->handle($request);
        $expectedDiscounts = [
            (new DiscountSerializer(
                sprintf(FreeProductsOfCategoryPerQuantity::MESSAGE, '2', '2'),
                'For every product of category "Switches" (id 2), when you buy five, you get a sixth for free'
            ))->serialize(),
            (new DiscountSerializer(
                sprintf(PercentageOnCheapestBought::MESSAGE, '20', 'B102', '8', '10'),
                'If you buy two or more products of category "Tools" (id 1), you get a 20% discount on the cheapest product.'
            ))->serialize()
        ];

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertJsonStringEqualsJsonString(json_encode($expectedDiscounts), (string) $response->getBody());
    }

    public function testInvalidBodyFormatMissingTotalDiscountsRequest(): void
    {
        $invalidRequestBody = [
            'id' => '3',
            'customer-id' => '3',
            'items' => [
                ['product-id' => 'A101', 'quantity' => '2', 'unit-price' => '9.75', 'total' => '19.50'],
                ['product-id' => 'A102', 'quantity' => '1', 'unit-price' => '49.50', 'total' => '49.50']
            ],
        ];

        $request = $this->createRequest('POST', '/discounts', $invalidRequestBody);
        $response = $this->app->handle($request);

        $this->assertEquals(400, $response->getStatusCode());
    }

    public function testInvalidBodyFormatMissingItemQuantityDiscountsRequest(): void
    {
        $invalidRequestBody = [
            'id' => '3',
            'customer-id' => '3',
            'items' => [
                ['product-id' => 'A101', 'unit-price' => '9.75', 'total' => '19.50'],
                ['product-id' => 'A102', 'quantity' => '1', 'unit-price' => '49.50', 'total' => '49.50']
            ],
            'total' => '430'
        ];

        $request = $this->createRequest('POST', '/discounts', $invalidRequestBody);
        $response = $this->app->handle($request);

        $this->assertEquals(400, $response->getStatusCode());
    }

    private function productsData(): array
    {
        return [
            ['id' => 'A101', 'description' => 'description', 'category' => '1', 'price' => '100'],
            ['id' => 'B101', 'description' => 'description', 'category' => '2', 'price' => '100'],
            ['id' => 'B102', 'description' => 'description', 'category' => '2', 'price' => '10'],
        ];
    }
}
