<?php
namespace App\Platforms;

use App\Exceptions\CoinNotFoundException;
use App\Exceptions\PlatformDriverDoNotSupportBuyAnyCoinException;
use App\Models\Coin;

abstract class Platform implements PlatformInterface
{
    public static string|null $driver_name = null;

    /**
     * get information of special coin.
     *
     * @param string $symbol
     * @return Coin
     * @throws CoinNotFoundException
     */
    public function getCoin(string $symbol) : Coin
    {
        throw (new CoinNotFoundException())->setCoin($symbol);
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
        throw new PlatformDriverDoNotSupportBuyAnyCoinException();
    }
}
