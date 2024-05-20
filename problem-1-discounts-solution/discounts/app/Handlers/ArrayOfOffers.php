<?php declare(strict_types = 1);

namespace App\Handlers;

use App\Services\AbstractOffer;

class ArrayOfOffers extends \ArrayObject
{
    /**
     * @param int|string $key
     * @param mixed      $val
     */
    public function offsetSet($key, $val)
    {
        if ($val instanceof AbstractOffer) {
            return parent::offsetSet($key, $val);
        }
        throw new \InvalidArgumentException('Value must be a instance of AbstractOffer');
    }
}
