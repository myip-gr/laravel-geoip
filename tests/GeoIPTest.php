<?php

declare(strict_types=1);

namespace InteractionDesignFoundation\GeoIP\Tests;

/**
 * @covers \InteractionDesignFoundation\GeoIP\GeoIP
 */
class GeoIPTest extends TestCase
{
    /** @test */
    public function should_get_usd_currency()
    {
        $geo_ip = $this->makeGeoIP();

        $this->assertSame($geo_ip->getCurrency('US'), 'USD');
    }

    /** @test */
    public function test_get_service()
    {
        $geo_ip = $this->makeGeoIP([
            'service' => 'maxmind_database',
        ]);

        // Get config values
        $config = $this->getConfig()['services']['maxmind_database'];
        unset($config['class']);

        $this->assertInstanceOf(\InteractionDesignFoundation\GeoIP\Contracts\ServiceInterface::class, $geo_ip->getService());
    }

    /** @test */
    public function test_get_cache()
    {
        $geo_ip = $this->makeGeoIP();

        $this->assertInstanceOf(\InteractionDesignFoundation\GeoIP\Cache::class, $geo_ip->getCache());
    }
}
