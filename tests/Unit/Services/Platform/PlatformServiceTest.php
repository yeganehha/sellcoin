<?php

namespace Tests\Unit\Services\Platform;

use App\Services\Platform\DriverService;
use App\Services\Platform\PlatformService;
use InvalidArgumentException;
use Tests\TestCase;


class PlatformServiceTest extends TestCase
{
    public $drivers ;
    public function setUp(): void
    {
        parent::setUp();
        $this->drivers = DriverService::listDrivers() ;
    }

    public function testInsert()
    {
        $inserted = PlatformService::insert('test' , $this->drivers->first());
        $this->assertDatabaseHas('platforms', [
            'id' => $inserted->id,
            'driver_name' => ($this->drivers->first())::class,
            'name' => 'test',
            'deposit_tether' => '0',
            'reserved_tether' => '0',
        ]);
    }
    public function testInvalidDriverInsert()
    {
        $this->expectException(InvalidArgumentException::class);
        PlatformService::insert('test' , self::class);
        PlatformService::insert('test' , $this);
    }

    public function testDepositTether()
    {
        $inserted = PlatformService::insert('test' , $this->drivers->first());
        PlatformService::depositTether($inserted , 10.536);
        $this->assertDatabaseHas('platforms', [
            'id' => $inserted->id,
            'driver_name' => ($this->drivers->first())::class,
            'name' => 'test',
            'deposit_tether' => 10.536,
            'reserved_tether' => 0,
        ]);
        PlatformService::depositTether($inserted->id , 10);
        $this->assertDatabaseHas('platforms', [
            'id' => $inserted->id,
            'driver_name' => ($this->drivers->first())::class,
            'name' => 'test',
            'deposit_tether' => 20.536,
            'reserved_tether' => 0,
        ]);
    }
    public function testInvalidPlatformDepositTether()
    {
        $this->expectException(InvalidArgumentException::class);
        PlatformService::depositTether(0 , 10.536);
    }

    public function testWithdrawTether()
    {
        $inserted = PlatformService::insert('test' , $this->drivers->first());
        PlatformService::depositTether($inserted , 10.536);
        PlatformService::withdrawTether($inserted , 5.036);
        $this->assertDatabaseHas('platforms', [
            'id' => $inserted->id,
            'driver_name' => ($this->drivers->first())::class,
            'name' => 'test',
            'deposit_tether' => 5.5,
            'reserved_tether' => 0,
        ]);
        $this->expectException(InvalidArgumentException::class);
        PlatformService::withdrawTether($inserted , 10);
    }

}
