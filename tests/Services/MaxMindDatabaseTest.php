<?php

declare(strict_types=1);

namespace InteractionDesignFoundation\GeoIP\Tests\Services;

use InteractionDesignFoundation\GeoIP\Tests\TestCase;

/**
 * @covers \InteractionDesignFoundation\GeoIP\Services\MaxMindDatabase
 */
class MaxMindDatabaseTest extends TestCase
{
    /** @test */
    public function should_return_config_value(): void
    {
        list($service, $config) = $this->getService();

        $this->assertSame($service->config('database_path'), $config['database_path']);
    }

    /** @test */
    public function should_return_valid_location(): void
    {
        [$service] = $this->getService();

        $location = $service->locate('81.2.69.142');

        $this->assertInstanceOf(\InteractionDesignFoundation\GeoIP\Location::class, $location);
        $this->assertSame($location->ip, '81.2.69.142');
        $this->assertSame($location->default, false);
    }

    /** @test */
    public function should_return_invalid_location_for_special_addresses(): void
    {
        [$service] = $this->getService();

        try {
            $location = $service->locate('1.1.1.1');
            $this->assertSame($location->default, false);
        } catch (\GeoIp2\Exception\AddressNotFoundException $e) {
            $this->assertSame($e->getMessage(), 'The address 1.1.1.1 is not in the database.');
        }
    }

    /** @return list{\InteractionDesignFoundation\GeoIP\Contracts\ServiceInterface, array<string, mixed>} */
    protected function getService(): array
    {
        $config = $this->getConfig()['services']['maxmind_database'];

        $service = new $config['class']($config);

        return [$service, $config];
    }
}
