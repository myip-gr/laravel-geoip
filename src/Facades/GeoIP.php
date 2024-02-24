<?php

namespace InteractionDesignFoundation\GeoIP\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @mixin \InteractionDesignFoundation\GeoIP\GeoIP
 */
class GeoIP extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'geoip';
    }
}