<?php declare(strict_types = 1);

namespace App\Services\DTO;

class ArrayOfItems extends \ArrayObject
{
    /**
     * @param int|string $key
     * @param mixed      $val
     */
    public function offsetSet($key, $val)
    {
        if ($val instanceof Item) {
            return parent::offsetSet($key, $val);
        }
        throw new \InvalidArgumentException('Value must be a instance of Item');
    }
}
