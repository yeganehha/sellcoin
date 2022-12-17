<?php

namespace App\Platforms\Drivers;

use App\Exceptions\CoinNotFoundException;
use App\Exceptions\PlatformDriverDoNotSupportBuyAnyCoinException;
use App\Models\Coin;
use Illuminate\Support\Collection;

class BinanceDriver extends \App\Platforms\Platform
{
    public static string|null $driver_name = "Binance";

    /**
     * get list of Coins and return in Collection.
     *
     * @return Collection < App\Model\Coin >
     */
    public function coins():Collection
    {
        // TODO:return collection of App\Model\Coin
    }

    /**
     * get information of special coin.
     *
     * @param string $symbol
     * @return Coin
     * @throws CoinNotFoundException
     */
    public function getCoin(string $symbol) : Coin
    {
        $coins = $this->coins();
        $coin = $coins->first(function ($coin) use($symbol) {
                                  return $coin->symbol == $symbol;
                              });
        if ( $coin == null )
            throw (new CoinNotFoundException())->setCoin($symbol);
        return $coin;
    }

    /**
     * buy custom amount of special coin.
     *
     * @param string $symbol coin symbol
     * @param float $amount amount of that coin
     * @return string transaction uid
     * @throws PlatformDriverDoNotSupportBuyAnyCoinException
     */
    public function buyCoin(string $symbol , float $amount) : string
    {
        // TODO:return string of transaction result
    }
}
