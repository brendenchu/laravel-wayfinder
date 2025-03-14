<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Wayfinder Package Routes
|--------------------------------------------------------------------------
|
| Here are the routes for the Wayfinder package. Feel free to modify
| this route group as needed, but ensure the search controller is connected.
|
*/

// Get prefix and middleware from config
$prefix = config('wayfinder.route_prefix');
$middleware = config('wayfinder.middleware');

Route::group(['prefix' => $prefix, 'middleware' => $middleware], function () {
    // Get controller class from the container to respect bindings
    $controller = config('wayfinder.controller.http');

    // Main search route that accepts the optional searchable parameter
    Route::get('/{searchable?}', $controller)
        ->name(config('wayfinder.route_name'));
});
