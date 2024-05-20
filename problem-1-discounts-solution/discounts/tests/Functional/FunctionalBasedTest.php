<?php declare(strict_types = 1);

namespace Tests\Functional;

use PHPUnit\Framework\TestCase;
use Slim\Psr7\Factory\ServerRequestFactory;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Psr7\Factory\StreamFactory;
use Slim\App;

class FunctionalBasedTest extends TestCase
{
    protected App $app;

    protected function setUp(): void
    {
        $settings = require __DIR__ . '/../../config/settings.php';
        $this->app = $settings();
    }

    protected function createRequest(string $method, string $uri, array $body): ServerRequestInterface
    {
        $factory = new ServerRequestFactory();
        $streamFactory = new StreamFactory();

        return $factory->createServerRequest($method, $uri)
            ->withBody($streamFactory->createStream(json_encode($body)))
            ->withHeader('Content-Type', 'application/json');
    }
}
