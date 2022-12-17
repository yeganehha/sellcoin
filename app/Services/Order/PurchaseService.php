<?php

namespace App\Services\Order;

use App\Exceptions\CoinNotFoundException;
use App\Jobs\PurchaseCancelJob;
use App\Models\Coin;
use App\Models\Order;
use App\Services\Platform\DriverService;
use App\Services\Platform\PlatformService;
use \InvalidArgumentException;
use Illuminate\Support\Facades\DB;

class PurchaseService
{

    /**
     * get maximum amount that user can but
     * @param mixed $platform
     * @param Coin|array|int $coin
     * @return float
     */
    public static function maximumAvailableAmount(mixed $platform , Coin|array|int $coin) : float
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

    /**
     * generate order price
     * @param Coin|array|int $coin
     * @param float $amount
     * @return float
     */
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

    /**
     * generate purchase order
     * @param mixed $platform
     * @param Coin|array $coin
     * @param float $amount
     * @return Order
     * @throws CoinNotFoundException
     * @throws \Throwable
     */
    public static function draft(mixed $platform , Coin|array $coin , float $amount ) : Order
    {
        DB::beginTransaction();
        $platform = PlatformService::find($platform);

        if ( is_array($coin) and isset($coin['id']))
            $coin = DriverService::coin($platform , $coin['id'] );
        elseif ( $coin instanceof Coin)
            $coin = DriverService::coin($platform , $coin->id );
        else
            throw new InvalidArgumentException('Unsupported coin insert.');

        $order_price = self::price($coin,$amount);

        $order = Order::insert($amount,$platform->id,$order_price,$coin);

        $cancelTime = now()->addMinutes(config('setting.purchase.wait_for_confirm' , 0));
        dispatch(new PurchaseCancelJob($order->id))->delay($cancelTime);

        $platform->updateTether($order_price);
        DB::commit();
        return $order;
    }

}
