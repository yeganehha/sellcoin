<?php

namespace Database\Seeders;

use App\Models\Platform;
use App\Platforms\Drivers\BinanceDriver;
use App\Platforms\Drivers\CoinExDriver;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PlatformSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
         Platform::create([
             'name' => "Binance",
             'deposit_tether' => 1000,
             'reserved_tether' => 0,
             'driver_name' => BinanceDriver::class,
         ]);
         Platform::create([
             'name' => "CoinEx",
             'deposit_tether' => 500,
             'reserved_tether' => 0,
             'driver_name' => CoinExDriver::class,
         ]);
    }
}
