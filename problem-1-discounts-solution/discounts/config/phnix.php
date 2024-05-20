<?php

use Dotenv\Dotenv;

require __DIR__ . '/../vendor/autoload.php';

$dotenv = Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->load();

return [
    'paths' => [
        'migrations' => __DIR__ . '/../db/migrations',
        'seeds' => __DIR__ . '/../db/seeds',
    ],
    'environments' => [
        'default_migration_table' => 'phinxlog',
        'default_environment' => 'development',
        'development' => [
            'adapter' => $_ENV['DB_ADAPTER'],
            'host' => $_ENV['DB_HOST'],
            'port' => $_ENV['DB_PORT'],
            'user' => $_ENV['DB_USER'],
            'pass' => $_ENV['DB_PASSWORD'],
            'name' => $_ENV['DB_NAME'],
            'charset' => 'utf8',
        ],
        'test' => [
            'adapter' => $_ENV['DB_ADAPTER'],
            'host' => $_ENV['DB_HOST'],
            'port' => $_ENV['DB_PORT'],
            'user' => $_ENV['DB_USER'],
            'pass' => $_ENV['DB_PASSWORD'],
            'name' => $_ENV['DB_NAME'],
            'charset' => 'utf8',
        ],
        'production' => [
            'adapter' => $_ENV['DB_ADAPTER'],
            'host' => $_ENV['DB_HOST'],
            'port' => $_ENV['DB_PORT'],
            'user' => $_ENV['DB_USER'],
            'pass' => $_ENV['DB_PASSWORD'],
            'name' => $_ENV['DB_NAME'],
            'charset' => 'utf8',
        ],
    ],
    'version_order' => 'creation'
];
