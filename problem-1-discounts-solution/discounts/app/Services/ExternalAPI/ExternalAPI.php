<?php declare(strict_types=1);

namespace App\Services\ExternalAPI;

use App\ExceptionsHandler\ExternalApiException;
use GuzzleHttp\ClientInterface;

use function PHPUnit\Framework\throwException;

class ExternalAPI
{
    private string $baseUrl;

    public function __construct(
        private readonly ClientInterface $httpClient,
        private readonly array $externalConfig,
    ) {
        $this->baseUrl = $this->externalConfig['base_url'];
    }

    public function getCustomerById(string $id): array
    {
        try {
            $response = $this->httpClient->get($this->baseUrl . '/customers/' . $id);
            $body = (string) $response->getBody();
            if ($response->getStatusCode() === 200) {
                return json_decode($body, true);
            }
        } catch (\Exception $e) {
            throw new ExternalApiException($e->getMessage());
        }
        return [];
    }

    /**
     * @param array<string> $ids
     */
    public function getProducts(array $ids = []): array
    {
        $queryString = http_build_query([
            'id' => $ids
        ]);

        try {
            $response = $this->httpClient->get($this->baseUrl . '/products?' . $queryString);
            $body = (string) $response->getBody();
            if ($response->getStatusCode() === 200) {
                return json_decode($body, true);
            }
        } catch (\Exception $e) {
            throw new ExternalApiException($e->getMessage());
        }
        return [];
    }
}
