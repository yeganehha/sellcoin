<?php

namespace App\Services\Order;

use App\Models\Coin;
use App\Services\Platform\PlatformService;

class PurchaseService
{

    public static function maximumAvailableAmount(mixed $platform ,Coin|array|int $coin) : float
    {
        $platform = PlatformService::find($platform);

        if ( $coin instanceof Coin)
            $coin = $coin->price ;
        elseif ( is_array($coin) and isset($coin['price']))
            $coin = $coin['price'] ;
        else
            $coin = (int) $coin ;
        $availableAmount = $platform->available_tether / $coin;
        $mult = pow(10, 8);
        return floor($availableAmount * $mult) / $mult;
    }

    public static function price(Coin|array|int $coin , float $amount) : float
    {
        if ( $coin instanceof Coin)
            $coin = $coin->price ;
        elseif ( is_array($coin) and isset($coin['price']))
            $coin = $coin['price'] ;
        else
            $coin = (int) $coin ;

        return $amount * $coin;
    }

}
