<?php

namespace Takielias\TablarKit\Facades;

use Illuminate\Support\Facades\Facade;

class TablarKit extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor(): string
    {
        return 'tablar-kit';
    }
}
