<?php declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

// Set up settings
$settings = require __DIR__ . '/../config/settings.php';
$app = $settings();

// Run App
$app->run();
