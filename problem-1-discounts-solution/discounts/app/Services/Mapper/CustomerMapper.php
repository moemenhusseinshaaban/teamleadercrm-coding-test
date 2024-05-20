<?php declare(strict_types = 1);

namespace App\Services\Mapper;

use App\ExceptionsHandler\InvalidDataMappingException;
use App\Services\DTO\Customer;

class CustomerMapper
{
    public static function map(array $data): Customer
    {
        try {
            $customer = new Customer(
                id: $data['id'],
                name: $data['name'],
                since: $data['name'],
                revenue: (float) $data['revenue']
            );
        } catch (\Exception $e) {
            throw new InvalidDataMappingException($e->getMessage());
        }

        return $customer;
    }
}
