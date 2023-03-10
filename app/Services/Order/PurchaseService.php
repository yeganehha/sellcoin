<?php

namespace App\Services\Order;

use App\Enums\OrderStatusEnum;
use App\Exceptions\CoinNotFoundException;
use App\Jobs\PurchaseCancelJob;
use App\Models\Coin;
use App\Models\Order;
use App\Services\Platform\DriverService;
use App\Services\Platform\PlatformService;
use InvalidArgumentException;
use Illuminate\Support\Facades\DB;
use Symfony\Component\Routing\Exception\InvalidParameterException;
use Throwable;

class PurchaseService
{

    /**
     * get maximum amount that user can but
     * @param mixed $platform
     * @param Coin|array|float $coin
     * @return float
     */
    public static function maximumAvailableAmount(mixed $platform , Coin|array|float $coin) : float
    {
        $platform = PlatformService::find($platform);

        if ( $coin instanceof Coin)
            $coin = $coin->price ;
        elseif ( is_array($coin) and isset($coin['price']))
            $coin = $coin['price'] ;
        else
            $coin = (float) $coin ;
        $availableAmount = $platform->available_tether / $coin;
        $mult = pow(10, 8);
        return floor($availableAmount * $mult) / $mult;
    }

    /**
     * generate order price
     * @param Coin|array|float $coin
     * @param float $amount
     * @return float
     */
    public static function price(Coin|array|float $coin , float $amount) : float
    {
        if ( $coin instanceof Coin)
            $coin = $coin->price ;
        elseif ( is_array($coin) and isset($coin['price']))
            $coin = $coin['price'] ;
        else
            $coin = (float) $coin ;

        return $amount * $coin;
    }



    public static function find( mixed $order) : Order
    {
        if ( $order instanceof Order)
            return $order;
        elseif ( is_object($order) )
            throw new InvalidArgumentException("Object You send is not valid Order!");
        elseif( (int) $order > 0 )
            return Order::findWithId((int) $order);
        throw new InvalidArgumentException("Object You send is not valid Order!");
    }

    /**
     * generate purchase order
     * @param mixed $platform
     * @param Coin|array $coin
     * @param float $amount
     * @param string $wallet
     * @return Order
     * @throws CoinNotFoundException
     * @throws Throwable
     */
    public static function draft(mixed $platform , Coin|array $coin , float $amount , string $wallet ) : Order
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

        $order = Order::insert($amount,$platform->id,$order_price,$coin,$wallet);

        $cancelTime = now()->addMinutes(config('setting.purchase.wait_for_confirm' , 0));
        dispatch(new PurchaseCancelJob($order->id))->delay($cancelTime);

        $platform->updateTether($order_price);
        DB::commit();
        return $order;
    }

    /**
     * confirm purchase order
     * @param mixed $order
     * @return Order
     * @throws Throwable
     */
    public static function confirm(mixed $order) : Order
    {
        DB::beginTransaction();
        $order = self::find($order);
        if ( $order->status != OrderStatusEnum::Wait)
            throw new InvalidParameterException("Order status not equal to `Wait` !");
        try{
            $transaction = $order->platform->driver->buyCoin($order->coin_id , $order->amount , $order->wallet);
        } catch (\Exception $exception) {
            return self::cancel($order);
        }
        $order = $order->edit(OrderStatusEnum::Paid , $transaction);

        $order->platform->reduceReservedTether($order->price);
        DB::commit();
        return $order;
    }

    /**
     * cancel purchase order
     * @param mixed $order
     * @return Order
     * @throws Throwable
     */
    public static function cancel(mixed $order) : Order
    {
        DB::beginTransaction();
        $order = self::find($order);
        if ( $order->status != OrderStatusEnum::Wait)
            throw new InvalidParameterException("Order status not equal to `Wait` !");

        $order = $order->edit(OrderStatusEnum::Cancel);

        $order->platform->updateTether($order->price , false);
        DB::commit();
        return $order;
    }

}
