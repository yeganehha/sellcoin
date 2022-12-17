<?php

namespace Database\Factories;

use App\Enums\OrderStatusEnum;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Order>
 */
class OrderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $curency = [
            [
                'id' => 'bitcoin',
                'symbol' => 'btc',
                'name' => 'Bitcoin',
                'current_price' => rand(10726, 26726) + 0.155461,
            ],
            [
                'id' => 'ethereum',
                'symbol' => 'eth',
                'name' => 'Ethereum',
                'current_price' => rand(1070, 2070) + 0.452584,
            ],
            [
                'id' => 'binancecoin',
                'symbol' => 'bnb',
                'name' => 'BNB',
                'current_price' => rand(200, 400) + 0.12,
            ]
        ];
        $coin = $curency[ rand(0,2)];
        $amount = rand(1,100) + ( rand(1,1000) / 1000);
        $price = $amount *  $coin['current_price'];
        switch (rand(1,2)) {
            case 1:
                $status = OrderStatusEnum::Paid;
                break;
            default:
                $status = OrderStatusEnum::Cancel;
                break;
        }
        return [
            'amount' =>  $amount ,
            'platform_id' => rand(1,2),
            'coin_id' => $coin['id'],
            'coin_symbol' => $coin['symbol'],
            'coin_name' => $coin['name'],
            'coin_price' => $coin['current_price'],
            'price' => $price,
            'status' => $status,
        ];
    }
}
