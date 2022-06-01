<?php

namespace KimSpeer\Sort\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \KimSpeer\Sort\Sort
 */
class Sort extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'sortlist';
    }
}
