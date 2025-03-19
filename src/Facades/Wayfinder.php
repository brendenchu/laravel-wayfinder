<?php

namespace Brendenchu\Wayfinder\Facades;

use Brendenchu\Wayfinder\Support\SearchConfig;
use Brendenchu\Wayfinder\Support\SearchResponse;
use Closure;
use Illuminate\Support\Facades\Facade;

/**
 * @method static SearchResponse search(array $params, string $searchableClass, ?Closure $callback = null)
 * @method static SearchConfig config(string $searchableClass)
 * @method static bool canSearch(string $searchableClass)
 * @method static array searchables()
 * @method static string url(string $searchableClass, array $params = [])
 *
 * @see \Brendenchu\Wayfinder\Wayfinder
 */
class Wayfinder extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor(): string
    {
        return 'wayfinder';
    }
}
