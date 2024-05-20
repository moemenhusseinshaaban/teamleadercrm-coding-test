<?php declare(strict_types = 1);

namespace App\Routes;

use App\Controllers\DiscountsController;
use App\Controllers\ExternalController;
use Slim\App;

return function (App $app) {

    $app->post('/discounts', [DiscountsController::class, 'discounts']);

    $app->get('/external/customers/{id}', [ExternalController::class, 'customer']);
    $app->get('/external/products', [ExternalController::class, 'products']);
};
