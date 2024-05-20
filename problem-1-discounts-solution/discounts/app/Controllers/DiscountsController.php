<?php declare(strict_types = 1);

namespace App\Controllers;

use App\Services\Mapper\OrderMapper;
use App\Validations\DiscountsRequestBodyValidator;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use App\Services\DiscountsCalculator;
use Respect\Validation\Exceptions\ValidationException;

class DiscountsController {

    public function __construct(
        private readonly DiscountsCalculator $discountsCalculator,
        private readonly DiscountsRequestBodyValidator $validator
    ) {
    }

    public function discounts(Request $request, Response $response): Response
    {
        $body = $request->getParsedBody();

        try {
            $this->validator->validate($body);
        } catch (ValidationException $e) {
            $response->getBody()->write($e->getMessage());
            return $response->withStatus(400)
                ->withHeader('Content-Type', 'application/json');
        }

        $order = OrderMapper::map($body);

        $discounts = $this->discountsCalculator->calculate($order);

        $response->getBody()->write(json_encode($discounts));
        return $response;
    }
}
