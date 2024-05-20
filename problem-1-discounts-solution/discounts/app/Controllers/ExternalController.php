<?php declare(strict_types = 1);

namespace App\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class ExternalController {

    public function customer(Request $request, Response $response, $args)
    {
        $customers = $this->externalData('customers.json');

        $result = [];
        foreach ($customers as $customer) {
            if ($customer['id'] == $args['id']) {
                $result = $customer;
                break;
            }
        }

        $response->getBody()->write(json_encode($result));
        return $response;
    }

    public function products(Request $request, Response $response)
    {
        $products = $this->externalData('products.json');

        $params = $request->getQueryParams();

        $result = isset($params['id'])
            ? $this->filterProductsPerIds($products, $params['id'])
            : $products;

        $response->getBody()->write(json_encode($result));
        return $response;
    }

    private function filterProductsPerIds(array $data, array $filter): array
    {
        $result = [];
        foreach ($data as $product) {
            foreach ($filter as $value) {
                if ($product['id'] == $value) {
                    $result[] = $product;
                }
            }
        }

        return $result ?: $data;
    }

    private function externalData(string $file): array
    {
        $jsonData = file_get_contents(__DIR__ . '/../ExternalData/'. $file);

        return json_decode($jsonData, true);
    }
}
