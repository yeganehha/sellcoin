<?php
namespace App\Platforms;

use App\Exceptions\CoinNotFoundException;
use App\Exceptions\PlatformDriverDoNotSupportBuyAnyCoinException;
use App\Models\Coin;
use Illuminate\Contracts\Support\Arrayable;

abstract class Platform implements PlatformInterface,Arrayable
{
    public static string|null $driver_name = null;

    /**
     * get information of special coin.
     *
     * @param mixed $id
     * @return Coin
     * @throws CoinNotFoundException
     */
    public function getCoin(mixed $id) : Coin
    {
        throw (new CoinNotFoundException())->setCoin($id);
    }

    /**
     * buy custom amount of special coin.
     *
     * @param mixed $coin coin
     * @param float $amount amount of that coin
     * @param string $wallet
     * @return string transaction uid
     * @throws PlatformDriverDoNotSupportBuyAnyCoinException
     */
    public function buyCoin(mixed $coin , float $amount , string $wallet) : string
    {
        throw new PlatformDriverDoNotSupportBuyAnyCoinException();
    }

    public function toArray()
    {
        return [
            'name' => get_class($this),
            'label' => get_class($this)::$driver_name,
        ];
    }
}
