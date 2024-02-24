<?php

declare(strict_types=1);

namespace InteractionDesignFoundation\GeoIP\Tests;

use Illuminate\Cache\CacheManager;
use Mockery;

/**
 * @covers \InteractionDesignFoundation\GeoIP\Cache
 */
class CacheTest extends TestCase
{
    /** @test */
    public function should_return_valid_location(): void
    {
        $data = [
            'ip' => '81.2.69.142',
            'iso_code' => 'US',
            'lat' => 41.31,
            'lon' => -72.92,
        ];

        $cacheMock = Mockery::mock(CacheManager::class)
            ->shouldAllowMockingProtectedMethods();

        $cacheMock->shouldReceive('supportsTags')->andReturn(false);
        $cacheMock->shouldReceive('get')->with($data['ip'])->andReturn($data);

        $geo_ip = $this->makeGeoIP([], $cacheMock);
        $geo_ip->getCache()->setPrefix('');

        $location = $geo_ip->getCache()->get($data['ip']);

        $this->assertInstanceOf(\InteractionDesignFoundation\GeoIP\Location::class, $location);
        $this->assertSame($location->ip, $data['ip']);
        $this->assertSame($location->default, false);
    }

    /** @test */
    public function should_return_invalid_location(): void
    {
        $cacheMock = Mockery::mock(CacheManager::class)
            ->shouldAllowMockingProtectedMethods();

        $cacheMock->shouldReceive('supportsTags')->andReturn(false);

        $geo_ip = $this->makeGeoIP([], $cacheMock);
        $geo_ip->getCache()->setPrefix('');

        $cacheMock->shouldReceive('get')->with('81.2.69.142')->andReturn(null);

        $this->assertSame($geo_ip->getCache()->get('81.2.69.142'), null);
    }

    /** @test */
    public function should_set_location(): void
    {
        $location = new \InteractionDesignFoundation\GeoIP\Location([
            'ip' => '81.2.69.142',
            'iso_code' => 'US',
            'lat' => 41.31,
            'lon' => -72.92,
        ]);

        $cacheMock = Mockery::mock(CacheManager::class)
            ->shouldAllowMockingProtectedMethods();

        $cacheMock->shouldReceive('supportsTags')->andReturn(false);

        $geo_ip = $this->makeGeoIP([], $cacheMock);
        $geo_ip->getCache()->setPrefix('');

        $cacheMock->shouldReceive('put')->withArgs(['81.2.69.142', $location->toArray(), $geo_ip->config('cache_expires')])->andReturn(null);

        $cacheMock->shouldReceive('tags')
            ->with($geo_ip->config('cache_tags'))
            ->andReturnSelf();

        $this->assertSame($geo_ip->getCache()->set('81.2.69.142', $location), null);
    }

    /** @test */
    public function should_flush_locations(): void
    {
        $cacheMock = Mockery::mock(CacheManager::class)
            ->shouldAllowMockingProtectedMethods();
        $cacheMock->shouldReceive('supportsTags')->andReturn(false);

        $geo_ip = $this->makeGeoIP([], $cacheMock);

        $cacheMock->shouldReceive('flush')->andReturn(true);

        $cacheMock->shouldReceive('tags')->with($geo_ip->config('cache_tags'))->andReturnSelf();

        $this->assertSame($geo_ip->getCache()->flush(), true);
    }
}
