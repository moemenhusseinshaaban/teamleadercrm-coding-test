<?php declare(strict_types = 1);

namespace App\Services\DiscountActions;

use App\Services\DiscountSerializer;

interface ActionInterface
{
    public function apply(): DiscountSerializer;
}
