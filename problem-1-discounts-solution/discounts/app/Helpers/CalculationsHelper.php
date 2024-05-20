<?php declare(strict_types=1);

namespace App\Helpers;

use App\Services\DTO\ArrayOfItems;
use App\Services\DTO\ArrayOfProducts;
use App\Services\DTO\Item;
use App\Services\DTO\Product;

class CalculationsHelper
{
    public static function percentage($amount, $percentage): float
    {
        return $amount - ($amount * $percentage / 100);
    }

    public static function countProductCategory(ArrayOfItems $items, ArrayOfProducts $products, string $categoryId): int
    {
        $count = 0;
        /** @var Item $item */
        foreach ($items as $item) {
            /** @var Product $itemProduct */
            $itemProduct = $products[$item->getProductId()];

            if ($itemProduct->getCategory() === $categoryId) {
                $count += $item->getQuantity();
            }
        }

        return $count;
    }

    public static function getCheapestItem(ArrayOfItems $items): Item
    {
        $cheapestItem = null;
        /** @var Item $item */
        foreach ($items as $item) {
            if (!$cheapestItem) {
                $cheapestItem = $item;
            }
            elseif ($item->getUnitPrice() < $cheapestItem->getUnitPrice()) {
                $cheapestItem = $item;
            }
        }

        return $cheapestItem;
    }
}

