<?php declare(strict_types = 1);

namespace App\Validations;

use Respect\Validation\Validator as v;

class DiscountsRequestBodyValidator
{
    public static function validate(array $body): void
    {
        $validator = v::arrayType()
            ->key('id', v::stringType()->notEmpty())
            ->key('customer-id', v::stringType()->notEmpty())
            ->key('items', v::arrayType()->each(
                v::arrayType()
                    ->key('product-id', v::stringType()->notEmpty())
                    ->key('quantity', v::stringType()->notEmpty())
                    ->key('unit-price', v::stringType()->notEmpty())
                    ->key('total', v::stringType()->notEmpty())
            ))
            ->key('total', v::stringType()->notEmpty());

        $validator->assert($body);
    }
}
