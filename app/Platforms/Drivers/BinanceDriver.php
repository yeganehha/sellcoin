<?php

namespace App\Platforms\Drivers;

use App\Exceptions\CoinNotFoundException;
use App\Exceptions\PlatformDriverDoNotSupportBuyAnyCoinException;
use App\Models\Coin;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\App;

class BinanceDriver extends \App\Platforms\Platform
{
    public static string|null $driver_name = "Binance";
    public static $handler;
    /**
     * get list of Coins and return in Collection.
     *
     * @return Collection < App\Model\Coin >
     */
    public function coins():Collection
    {
        $config['base_uri'] = 'https://api.coingecko.com';
        if (App::runningUnitTests() and self::$handler != null )
            $config['handler'] = self::$handler;
        $client = new Client($config);
        $options['Accept'] = 'application/json';
        $options['timeout'] = 5;
        $options['connect_timeout'] = 5;
        $request = $client->request("GET",'/api/v3/coins/markets?vs_currency=usd',$options);
        $body = $request->getBody()->getContents();
        $coins = json_decode($body,true) ?? [];
        $coins = collect($coins);
        return $coins->map(function ($value) {
            $coin = new Coin();
            $value['price'] = $value['current_price'];
            $coin->fill($value);
            return $coin;
        });
    }

    /**
     * get information of special coin.
     *
     * @param mixed $id
     * @return Coin
     * @throws CoinNotFoundException
     */
    public function getCoin(mixed $id) : Coin
    {
        $config['base_uri'] = 'https://api.coingecko.com';
        if (App::runningUnitTests() and self::$handler != null )
            $config['handler'] = self::$handler;
        $client = new Client($config);
        $options['Accept'] = 'application/json';
        $request = $client->request("GET",'/api/v3/coins/'.$id,$options);
        $body = $request->getBody()->getContents();
        $value = json_decode($body);
        $coin = new Coin();
        $coin->id = $value->id;
        $coin->image = $value->image->small;
        $coin->symbol = $value->symbol;
        $coin->name = $value->name;
        $coin->price = $value->market_data->current_price->usd;
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
