<?php

namespace Brendenchu\Wayfinder;

use Brendenchu\Wayfinder\Http\Controllers\SearchController;
use Brendenchu\Wayfinder\Http\Requests\SearchRequest;
use Illuminate\Foundation\AliasLoader;
use Illuminate\Support\ServiceProvider;

class WayfinderServiceProvider extends ServiceProvider
{
    /**
     * Register any package services.
     *
     * @return void
     */
    public function register()
    {
        // Merge config
        $this->mergeConfigFrom(
            __DIR__ . '/../config/wayfinder.php', 'wayfinder'
        );

        // Register search service singleton
        $this->app->singleton('wayfinder', function () {
            return new Wayfinder();
        });

        // Register facade alias
        $this->app->booting(function () {
            $loader = AliasLoader::getInstance();
            $loader->alias('Wayfinder', \Brendenchu\Wayfinder\Facades\Wayfinder::class);
        });

        // Bind the controller class - check config first, then published custom controller
        $configController = config('wayfinder.controller.http');
        $customControllerPath = app_path('Http/Controllers/Vendor/Wayfinder/SearchController.php');

        if ($configController && class_exists($configController)) {
            // Use the controller specified in config
            $this->app->bind(
                SearchController::class,
                $configController
            );
        } elseif (file_exists($customControllerPath)) {
            // Or use the published custom controller if it exists
            $this->app->bind(
                SearchController::class,
                'App\\Http\\Controllers\\Vendor\\Wayfinder\\SearchController'
            );
        }

        // Bind the request class - check config first, then published custom request
        $configRequest = config('wayfinder.request.http');
        $customRequestPath = app_path('Http/Requests/Vendor/Wayfinder/SearchRequest.php');

        if ($configRequest && class_exists($configRequest)) {
            // Use the request specified in config
            $this->app->bind(
                SearchRequest::class,
                $configRequest
            );
        } elseif (file_exists($customRequestPath)) {
            // Or use the published custom request if it exists
            $this->app->bind(
                SearchRequest::class,
                'App\\Http\\Requests\\Vendor\\Wayfinder\\SearchRequest'
            );
        }
    }

    /**
     * Bootstrap any package services.
     *
     * @return void
     */
    public function boot()
    {
        // Load routes
        $this->loadRoutesFrom(__DIR__ . '/../routes/web.php');

        // Load views
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'wayfinder');

        // Publish configuration
        $this->publishes([
            __DIR__ . '/../config/wayfinder.php' => config_path('wayfinder.php'),
        ], 'wayfinder-config');

        // Publish views
        $this->publishes([
            __DIR__ . '/../resources/views' => resource_path('views/vendor/wayfinder'),
        ], 'wayfinder-views');

        // Publish controllers
        $this->publishes([
            __DIR__ . '/Controllers/SearchController.php' => app_path('Http/Controllers/Vendor/Wayfinder/SearchController.php'),
        ], 'wayfinder-controllers');

        // Publish request classes
        $this->publishes([
            __DIR__ . '/Requests/SearchRequest.php' => app_path('Http/Requests/Vendor/Wayfinder/SearchRequest.php'),
        ], 'wayfinder-requests');

        // Publish everything at once
        $this->publishes([
            __DIR__ . '/../config/wayfinder.php' => config_path('wayfinder.php'),
            __DIR__ . '/../resources/views' => resource_path('views/vendor/wayfinder'),
            __DIR__ . '/Http/Controllers/SearchController.php' => app_path('Http/Controllers/Vendor/Wayfinder/SearchController.php'),
            __DIR__ . '/Http/Requests/SearchRequest.php' => app_path('Http/Requests/Vendor/Wayfinder/SearchRequest.php'),
        ], 'wayfinder-all');
    }
}
