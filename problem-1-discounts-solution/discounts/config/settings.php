<?php declare(strict_types=1);

use App\Controllers\DiscountsController;
use App\Middleware\JsonContentTypeMiddleware;
use App\Models\Rule;
use App\Services\DiscountsCalculator;
use App\Services\ExternalAPI\CustomerClient;
use App\Services\ExternalAPI\ExternalAPI;
use App\Services\ExternalAPI\ProductClient;
use App\Repositories\RuleRepository;
use App\Validations\DiscountsRequestBodyValidator;
use DI\ContainerBuilder;
use Doctrine\DBAL\DriverManager;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Mapping\Driver\AttributeDriver;
use Doctrine\ORM\Configuration;
use Dotenv\Dotenv;
use Psr\Container\ContainerInterface;
use Slim\Factory\AppFactory;
use Symfony\Component\Yaml\Yaml;

return function () {

    $containerBuilder = new ContainerBuilder();

    $dotenv = Dotenv::createImmutable(__DIR__ . '/..');
    $dotenv->load();

    $loadConfigWithEnvVars = function (string $filePath): array {
        $config = Yaml::parseFile($filePath);
        array_walk_recursive($config, function (&$value) {
            if (is_string($value) && preg_match('/^%env\((.+)\)%$/', $value, $matches)) {
                $envVar = $matches[1];
                $value = $_ENV[$envVar] ?? null;
            }
        });
        return $config;
    };

    $doctrineConfig = $loadConfigWithEnvVars(__DIR__ . '/doctrine.yml');

    $settings = [
        'displayErrorDetails' => (bool) $_ENV['DISPLAY_ERROR_DETAILS'],
        'addContentLengthHeader' => false,
        'routerCacheFile' => (bool) $_ENV['ROUTER_CACHE_FILE'],
        'doctrine' => $doctrineConfig['doctrine'],
    ];

    $containerBuilder->addDefinitions([
        'settings' => $settings,
        EntityManager::class => function (ContainerInterface $container) {
            $doctrineConfig = $container->get('settings')['doctrine'];

            $config = new Configuration();
            $driver = new AttributeDriver($doctrineConfig['orm']['entity_paths']);
            $config->setMetadataDriverImpl($driver);
            $config->setAutoGenerateProxyClasses($doctrineConfig['orm']['auto_generate_proxy_classes']);
            $config->setProxyNamespace($doctrineConfig['orm']['proxy_namespace']);
            $config->setProxyDir($doctrineConfig['orm']['proxy_dir']);

            $connection = DriverManager::getConnection($doctrineConfig['dbal']);

            return new EntityManager($connection, $config);
        },
    ]);

    $container = $containerBuilder->build();

    $config = require __DIR__ . '/../config/external.php';

    $container->set('externalConfig', $config);

    $container->set('HttpClient', function() {
        return new GuzzleHttp\Client();
    });

    $container->set(ExternalAPI::class, function ($container) {
        $httpClient = $container->get('HttpClient');
        $externalConfig = $container->get('externalConfig');

        return new ExternalAPI($httpClient, $externalConfig);
    });

    $container->set(CustomerClient::class, function ($container) {
        $externalAPI = $container->get(ExternalAPI::class);

        return new CustomerClient($externalAPI);
    });

    $container->set(ProductClient::class, function ($container) {
        $externalAPI = $container->get(ExternalAPI::class);

        return new ProductClient($externalAPI);
    });

    $container->set(RuleRepository::class, function ($container) {
        return $container->get(EntityManager::class)->getRepository(Rule::class);
    });

    $container->set(DiscountsCalculator::class, function ($container) {
        $ruleRepository = $container->get(RuleRepository::class);
        $customerClient = $container->get(CustomerClient::class);
        $productClient = $container->get(ProductClient::class);

        return new DiscountsCalculator($ruleRepository, $customerClient, $productClient);
    });

    $container->set(DiscountsController::class, function ($container) {
        $discountsRequestBodyValidator = $container->get(DiscountsRequestBodyValidator::class);
        $calculatorService = $container->get(DiscountsCalculator::class);

        return new DiscountsController($calculatorService, $discountsRequestBodyValidator);
    });

    AppFactory::setContainer($container);
    $app = AppFactory::create();
    $app->addBodyParsingMiddleware();
    $app->addErrorMiddleware($settings['displayErrorDetails'], true, true);
    $app->addRoutingMiddleware();
    $app->add(JsonContentTypeMiddleware::class);
    $routes = require __DIR__ . '/../app/Routes/web.php';
    $routes($app);

    return $app;
};
