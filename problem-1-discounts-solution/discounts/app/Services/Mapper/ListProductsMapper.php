<?php declare(strict_types = 1);

namespace App\Services\Mapper;

use App\ExceptionsHandler\InvalidDataMappingException;
use App\Services\DTO\ArrayOfProducts;

class ListProductsMapper
{
    public static function map(array $data): ArrayOfProducts
    {
        try {
            $products = new ArrayOfProducts();

            foreach ($data as $product) {
                $products[$product['id']] = ProductMapper::map($product);
            }
        } catch (\Exception $e) {
            throw new InvalidDataMappingException($e->getMessage());
        }

        return $products;
    }
}
