<?php

namespace InteractionDesignFoundation\GeoIP\Tests\Services;

use InteractionDesignFoundation\GeoIP\Tests\TestCase;

class MaxMindDatabaseTest extends TestCase
{
    /** @test */
    public function shouldReturnConfigValue()
    {
        list($service, $config) = $this->getService();

        $this->assertEquals($service->config('database_path'), $config['database_path']);
    }

    /** @test */
    public function shouldReturnValidLocation()
    {
        [$service] = $this->getService();

        $location = $service->locate('81.2.69.142');

        $this->assertInstanceOf(\InteractionDesignFoundation\GeoIP\Location::class, $location);
        $this->assertEquals($location->ip, '81.2.69.142');
        $this->assertEquals($location->default, false);
    }

    /** @test */
    public function shouldReturnInvalidLocationForSpecialAddresses()
    {
        [$service] = $this->getService();

        try {
            $location = $service->locate('1.1.1.1');
            $this->assertEquals($location->default, false);
        }
        catch (\GeoIp2\Exception\AddressNotFoundException $e) {
            $this->assertEquals($e->getMessage(), 'The address 1.1.1.1 is not in the database.');
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
