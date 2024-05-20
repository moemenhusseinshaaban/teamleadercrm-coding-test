<?php declare(strict_types = 1);

namespace App\Services\Mapper;

use App\ExceptionsHandler\InvalidDataMappingException;
use App\Services\DTO\Product;

class ProductMapper
{
    public static function map(array $data): Product
    {
        try {
            $product = new Product(
                id: $data['id'],
                description: $data['description'],
                category: $data['category'],
                price: (float) $data['price']
            );
        } catch (\Exception $e) {
            throw new InvalidDataMappingException($e->getMessage());
        }

        return $product;
    }
}
