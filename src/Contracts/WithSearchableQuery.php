<?php

namespace Brendenchu\Wayfinder\Contracts;

use Closure;

interface WithSearchableQuery
{
    /**
     * Returns a custom search implementation for this searchable class.
     *
     * @return Closure|null
     */
    public static function searchableQuery(): ?Closure;
}
