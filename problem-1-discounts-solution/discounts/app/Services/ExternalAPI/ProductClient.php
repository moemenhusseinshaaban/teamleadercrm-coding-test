<?php declare(strict_types = 1);

namespace App\Services\ExternalAPI;

use App\Services\DTO\ArrayOfItems;
use App\Services\DTO\ArrayOfProducts;
use App\Services\DTO\Item;
use App\Services\Mapper\ListProductsMapper;

class ProductClient
{
    public function __construct(private readonly ExternalAPI $externalAPI)
    {
    }

    public function fetchProducts(ArrayOfItems $items): ArrayOfProducts
    {
        $productIds = [];
        /** @var Item $item */
        foreach ($items as $item) {
            $productIds[] = $item->getProductId();
        }

        $products = $this->externalAPI->getProducts($productIds);

        return ListProductsMapper::map($products);
    }
}
