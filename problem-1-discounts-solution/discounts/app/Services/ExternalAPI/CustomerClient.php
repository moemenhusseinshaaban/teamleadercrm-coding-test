<?php declare(strict_types = 1);

namespace App\Services\ExternalAPI;

use App\Services\DTO\Customer;
use App\Services\Mapper\CustomerMapper;

class CustomerClient
{
    public function __construct(private readonly ExternalAPI $externalAPI)
    {
    }

    public function fetchCustomer(string $customerId): ?Customer
    {
        $customer = $this->externalAPI->getCustomerById($customerId);

        return !empty($customer) ? CustomerMapper::map($customer) : null;
    }
}
