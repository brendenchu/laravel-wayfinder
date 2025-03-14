<?php

namespace Brendenchu\Wayfinder\Contracts;

interface WithSearchable
{
    /**
     * Returns the search configuration for this searchable class.
     *
     * @return array
     */
    public static function searchable(): array;
}
