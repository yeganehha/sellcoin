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

class CoinExDriver extends \App\Platforms\Platform
{
    public static string|null $driver_name = "CoinEx";
    public static $handler;

    private $mockResponse =[
            [
                "id" => "bitcoin",
                "symbol" => "btc",
                "name" => "Bitcoin",
                "image" => "https://assets.coingecko.com/coins/images/1/large/bitcoin.png?1547033579",
                "current_price" => 16724.77,
                "last_updated" => "2022-12-17T08:57:55.513Z"
            ],
            [
                "id" => "ethereum",
                "symbol" => "eth",
                "name" => "Ethereum",
                "image" => "https://assets.coingecko.com/coins/images/279/large/ethereum.png?1595348880",
                "current_price" => 1180.73,
                "last_updated" => "2022-12-17T08:58:25.593Z"
            ],
            [
                "id" => "tether",
                "symbol" => "usdt",
                "name" => "Tether",
                "image" => "https://assets.coingecko.com/coins/images/325/large/Tether.png?1668148663",
                "current_price" => 1,
                "last_updated" => "2022-12-17T08:55:33.334Z"
            ],
            [
                "id" => "usd-coin",
                "symbol" => "usdc",
                "name" => "USD Coin",
                "image" => "https://assets.coingecko.com/coins/images/6319/large/USD_Coin_icon.png?1547042389",
                "current_price" => 0.998681,
                "last_updated" => "2022-12-17T08:58:43.614Z"
            ],
            [
                "id" => "binancecoin",
                "symbol" => "bnb",
                "name" => "BNB",
                "image" => "https://assets.coingecko.com/coins/images/825/large/bnb-icon2_2x.png?1644979850",
                "current_price" => 235.31,
                "last_updated" => "2022-12-17T08:58:12.902Z"
            ],
            [
                "id" => "binance-usd",
                "symbol" => "busd",
                "name" => "Binance USD",
                "image" => "https://assets.coingecko.com/coins/images/9576/large/BUSD.png?1568947766",
                "current_price" => 0.999684,
                "last_updated" => "2022-12-17T08:58:40.510Z"
            ],
            [
                "id" => "ripple",
                "symbol" => "xrp",
                "name" => "XRP",
                "image" => "https://assets.coingecko.com/coins/images/44/large/xrp-symbol-white-128.png?1605778731",
                "current_price" => 0.355687,
                "last_updated" => "2022-12-17T08:58:30.279Z"
            ],
            [
                "id" => "dogecoin",
                "symbol" => "doge",
                "name" => "Dogecoin",
                "image" => "https://assets.coingecko.com/coins/images/5/large/dogecoin.png?1547792256",
                "current_price" => 0.077721,
                "last_updated" => "2022-12-17T08:58:50.935Z"
            ],
            [
                "id" => "cardano",
                "symbol" => "ada",
                "name" => "Cardano",
                "image" => "https://assets.coingecko.com/coins/images/975/large/cardano.png?1547034860",
                "current_price" => 0.265564,
                "last_updated" => "2022-12-17T08:58:57.143Z"
            ],
            [
                "id" => "matic-network",
                "symbol" => "matic",
                "name" => "Polygon",
                "image" => "https://assets.coingecko.com/coins/images/4713/large/matic-token-icon.png?1624446912",
                "current_price" => 0.809627,
                "last_updated" => "2022-12-17T08:58:35.108Z"
            ],
            [
                "id" => "okb",
                "symbol" => "okb",
                "name" => "OKB",
                "image" => "https://assets.coingecko.com/coins/images/4463/large/WeChat_Image_20220118095654.png?1642471050",
                "current_price" => 22.35,
                "last_updated" => "2022-12-17T08:58:28.881Z"
            ]
        ];


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
        elseif ( config('app.debug' , false) )
        {
            $mock = new MockHandler([
                new Response(200,[], json_encode($this->mockResponse)),
            ]);
            $handlerStack = HandlerStack::create($mock);
            $config['handler']  = $handlerStack;
        }
        $client = new Client($config);
        $options['Accept'] = 'application/json';
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
        $coins = $this->coins();
        $coin = $coins->first(function ($coin) use($id) {
                                  return $coin->id == $id;
                              });
        if ( $coin == null )
            throw (new CoinNotFoundException())->setCoin($id);
        return $coin;
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
        return "cex-id-".time();
    }
}
