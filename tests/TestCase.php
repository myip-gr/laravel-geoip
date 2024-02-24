<?php

declare(strict_types=1);

namespace InteractionDesignFoundation\GeoIP\Tests;

use Illuminate\Cache\CacheManager;
use Mockery;
use PHPUnit\Framework\TestCase as PHPUnitTestCase;

/**
 * @coversNothing
 */
class TestCase extends PHPUnitTestCase
{
    public static $functions;

    protected function setUp(): void
    {
        self::$functions = Mockery::mock();
    }

    protected function tearDown(): void
    {
        Mockery::close();
    }

    protected function makeGeoIP(array $config = [], $cacheMock = null)
    {
        $cacheMock = $cacheMock ?: Mockery::mock(CacheManager::class);
        $cacheMock->shouldReceive('supportsTags')->andReturn(false);

        $config = array_merge($this->getConfig(), $config);

        $cacheMock->shouldReceive('tags')->with(['laravel-geoip-location'])->andReturnSelf();

        return new \InteractionDesignFoundation\GeoIP\GeoIP($config, $cacheMock);
    }

    protected function getConfig()
    {
        $config = include(__DIR__ . '/../config/geoip.php');

        $this->databaseCheck($config['services']['maxmind_database']['database_path']);

        return $config;
    }

    /**
     * Check for test database and make a copy of it
     * if it does not exist.
     *
     * @param string $database
     */
    protected function databaseCheck($database)
    {
        if (file_exists($database) === false) {
            @mkdir(dirname($database), 0755, true);
            copy(__DIR__ . '/../resources/geoip.mmdb', $database);
        }
    }
}
