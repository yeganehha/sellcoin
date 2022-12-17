<?php

namespace App\Platforms;

use App\Exceptions\CoinNotFoundException;
use App\Exceptions\PlatformDriverDoNotSupportBuyAnyCoinException;
use App\Models\Coin;
use Illuminate\Support\Collection;

/**
 * @property string $driver_name
 */
interface PlatformInterface
{
    /**
     * get list of Coins and return in Collection.
     *
     * @return Collection < App\Model\Coin >
     */
    public function coins():Collection;

    /**
     * get information of special coin.
     *
     * @param mixed $id
     * @return Coin
     * @throws CoinNotFoundException
     */
    public function getCoin(mixed $id) : Coin;


    /**
     * buy custom amount of special coin.
     *
     * @param mixed $coin coin
     * @param float $amount amount of that coin
     * @return string transaction uid
     * @throws PlatformDriverDoNotSupportBuyAnyCoinException
     */
    public function buyCoin(mixed $coin , float $amount) : string;
}
