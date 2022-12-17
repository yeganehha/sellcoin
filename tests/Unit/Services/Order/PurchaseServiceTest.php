<?php

namespace Tests\Unit\Services\Order;

use App\Enums\OrderStatusEnum;
use App\Jobs\PurchaseCancelJob;
use App\Models\Coin;
use App\Services\Order\PurchaseService;
use App\Services\Platform\DriverService;
use App\Services\Platform\PlatformService;
use Illuminate\Foundation\Testing\WithoutEvents;
use Tests\TestCase;


class PurchaseServiceTest extends TestCase
{
    use WithoutEvents;
    public $drivers ;
    public function setUp(): void
    {
        parent::setUp();
        $this->drivers = DriverService::listDrivers() ;
    }
    public function testMaximumAvailableAmount()
    {
        $inserted = PlatformService::insert('test' , $this->drivers->first() ,1000);
        $coin = new Coin();
        $coin->price = 100;
        $this->assertEquals( PurchaseService::maximumAvailableAmount($inserted,$coin) , 10);
    }

    public function testMaximumAvailableAmountWithInt()
    {
        $inserted = PlatformService::insert('test' , $this->drivers->first() ,1000);
        $coin = 100;
        $this->assertEquals( PurchaseService::maximumAvailableAmount($inserted,$coin) , 10);
    }

    public function testConfirm()
    {
        $this->expectsJobs(PurchaseCancelJob::class);
        $inserted = PlatformService::insert('test' , $this->drivers->last() ,1000);
        $coin = new Coin();
        $coin->id = 'tether';
        $coin->name = 'Tether';
        $coin->price = 1;
        $coin->symbol = 'tether';
        $order = PurchaseService::draft($inserted , $coin,2.5) ;
        PurchaseService::confirm($order);
        $this->assertDatabaseHas('orders', [
            'id' => $order->id,
            'coin_id' => 'tether',
            'price' => 2.5 ,
            'status' => OrderStatusEnum::Paid,
        ]);
    }

    public function testCancel()
    {
        $this->expectsJobs(PurchaseCancelJob::class);
        $inserted = PlatformService::insert('test' , $this->drivers->last() ,1000);
        $coin = new Coin();
        $coin->id = 'tether';
        $coin->name = 'Tether';
        $coin->price = 1;
        $coin->symbol = 'tether';
        $order = PurchaseService::draft($inserted , $coin,2.5) ;
        PurchaseService::cancel($order);
        $this->assertDatabaseHas('orders', [
            'id' => $order->id,
            'coin_id' => 'tether',
            'price' => 2.5 ,
            'status' => OrderStatusEnum::Cancel,
        ]);
    }

    public function testPrice()
    {
        $this->assertEquals( PurchaseService::price(0.123 , 2.5) , 2.5 * 0.123);
    }

    public function testFind()
    {
        $this->expectsJobs(PurchaseCancelJob::class);
        $inserted = PlatformService::insert('test' , $this->drivers->last() ,1000);
        $coin = new Coin();
        $coin->id = 'tether';
        $coin->name = 'Tether';
        $coin->price = 1;
        $coin->symbol = 'tether';
        $order = PurchaseService::draft($inserted , $coin,2.5) ;
        $this->assertEquals($order->id , PurchaseService::find($order)->id);

    }

    public function testDraft()
    {
        $this->expectsJobs(PurchaseCancelJob::class);
        $inserted = PlatformService::insert('test' , $this->drivers->last() ,1000);
        $coin = new Coin();
        $coin->id = 'tether';
        $coin->name = 'Tether';
        $coin->price = 1;
        $coin->symbol = 'tether';
        $order = PurchaseService::draft($inserted , $coin,2.5) ;
        $this->assertDatabaseHas('orders', [
            'id' => $order->id,
            'coin_id' => 'tether',
            'price' => 2.5 ,
            'status' => OrderStatusEnum::Wait,
        ]);
    }
}
