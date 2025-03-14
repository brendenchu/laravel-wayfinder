<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Search Routes Configuration
    |--------------------------------------------------------------------------
    |
    | Configure the routing behaviour for the search package. The route prefix
    | is used to define the URL path for search results. You can also specify
    | middleware to apply to the search routes, and the name of the route.
    |
    */
    'route_prefix' => 'search',
    'route_name' => 'wayfinder',
    'middleware' => ['web'],

    /*
    |--------------------------------------------------------------------------
    | Search Controller and Request Classes
    |--------------------------------------------------------------------------
    |
    | Specify the controller and request classes that handle search requests.
    | You can replace these with your own classes to customize functionality.
    |
    */
    'controller' => [
        'http' => \Brendenchu\Wayfinder\Http\Controllers\SearchController::class,
    ],
    'request' => [
        'http' => \Brendenchu\Wayfinder\Http\Requests\SearchRequest::class,
    ],

    /*
    |--------------------------------------------------------------------------
    | Search View Template and Response Variable
    |--------------------------------------------------------------------------
    |
    | Specify the view to render search results and the variable name to use
    | when passing the search response to the view. This can be overridden in
    | the searchable configuration.
    |
    */
    'view' => [
        'template_name' => 'wayfinder::search',
        'response_name' => 'wayfinder',
    ],

    /*
    |--------------------------------------------------------------------------
    | Searchable Classes and Route Parameters
    |--------------------------------------------------------------------------
    |
    | Define which Eloquent models or other classes are searchable and map them
    | to route parameters. The key is used in the URL (e.g., /search/products),
    | and the value is the fully qualified class name.
    |
    */
    'searchables' => [
        // 'products' => \App\Models\Product::class,
        // 'users' => \App\Models\User::class,
    ],

    /*
    |--------------------------------------------------------------------------
    | Default Searchable Class
    |--------------------------------------------------------------------------
    |
    | Specify the default searchable to use when none is provided in the URL.
    | This should be a key from the searchables array above. If null, the
    | default searchable will be the first one in the array.
    |
    */
    'default_searchable' => null,

    /*
    |--------------------------------------------------------------------------
    | Pagination Settings
    |--------------------------------------------------------------------------
    |
    | Configure how many results to show per page in search results.
    |
    */
    'per_page' => 15,
];
