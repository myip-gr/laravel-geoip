<?php

declare(strict_types=1);

namespace InteractionDesignFoundation\GeoIP;

use Illuminate\Support\ServiceProvider;

class GeoIPServiceProvider extends ServiceProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->registerGeoIpService();

        $this->registerResources();
        $this->registerGeoIpCommands();

        $this->mergeConfigFrom(__DIR__ . '/../config/geoip.php', 'geoip');
    }

    /**
     * Register currency provider.
     *
     * @return void
     */
    public function registerGeoIpService()
    {
        $this->app->singleton('geoip', function ($app) {
            return new GeoIP(
                $app->config->get('geoip', []),
                $app['cache']
            );
        });
    }

    /**
     * Register resources.
     *
     * @return void
     */
    public function registerResources()
    {
        $this->publishes([
            __DIR__ . '/../config/geoip.php' => config_path('geoip.php'),
        ], 'config');
    }

    /**
     * Register commands.
     *
     * @return void
     */
    public function registerGeoIpCommands()
    {
        $this->commands([
            Console\Update::class,
            Console\Clear::class,
        ]);
    }
}
