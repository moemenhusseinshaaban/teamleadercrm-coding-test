<?php declare(strict_types = 1);

namespace App\Services\Mapper;

use App\ExceptionsHandler\InvalidDataMappingException;
use App\Services\DTO\Item;

class ItemMapper
{
    public static function map(array $data): Item
    {
        try {
            $item = new Item(
                productId: $data['product-id'],
                quantity: (int) $data['quantity'],
                unitPrice: (float) $data['unit-price'],
                total: (float) $data['total']
            );
        } catch (\Exception $e) {
            throw new InvalidDataMappingException($e->getMessage());
        }

        return $item;
    }
}
