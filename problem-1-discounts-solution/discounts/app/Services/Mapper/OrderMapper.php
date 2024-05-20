<?php declare(strict_types = 1);

namespace App\Services\Mapper;

use App\ExceptionsHandler\InvalidDataMappingException;
use App\Services\DTO\ArrayOfItems;
use App\Services\DTO\Order;

class OrderMapper
{
    public static function map(array $data): Order
    {
        try {
            $items = new ArrayOfItems();
            foreach ($data['items'] as $item) {
                $items->append(
                    ItemMapper::map($item)
                );
            }

            $order = new Order(
                id: $data['id'],
                customerId: $data['customer-id'],
                items: $items,
                total: (float) $data['total']
            );
        } catch (\Exception $e) {
            throw new InvalidDataMappingException($e->getMessage());
        }

        return $order;
    }
}
