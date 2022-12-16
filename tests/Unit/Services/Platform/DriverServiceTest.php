<?php

namespace Tests\Unit\Services\Platform;

use App\Platforms\Drivers\BinanceDriver;
use App\Platforms\Drivers\CoinExDriver;
use App\Services\Platform\DriverService;
use InvalidArgumentException;
use Tests\TestCase;

class DriverServiceTest extends TestCase
{

    public function testValidDriver()
    {
        $this->assertTrue(DriverService::isValidDriver(BinanceDriver::class));
    }

    public function testInValidDriver()
    {
        $this->assertFalse(DriverService::isValidDriver(self::class));
    }

    public function testDriverName()
    {
        $this->assertEquals(BinanceDriver::$driver_name , DriverService::driverName(BinanceDriver::class));
        $this->assertEquals(CoinExDriver::$driver_name , DriverService::driverName(CoinExDriver::class));
    }

    public function testInvalidDriverName()
    {
        $this->expectException(InvalidArgumentException::class);
        DriverService::driverName(self::class);
    }

    public function testListDrivers()
    {
        $this->assertArrayHasKey(BinanceDriver::class , DriverService::listDrivers());
        $this->assertArrayHasKey(CoinExDriver::class , DriverService::listDrivers());
    }

}
