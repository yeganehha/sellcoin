<?php

return [
    'coins' => [
        'list' => [
            'cache_time' => env('COINS_LIST_CACHE' , 5 * 60) // time of caching list of coins per each exchange platform
        ],
        'cache_time' => env('COIN_CACHE' , 60) // time of caching information of special coin
    ],
    'purchase' => [
        'wait_for_confirm' => env('PURCHASE_WAIT_FOR_CONFIRM' , 5) // minute wait before auto cancel  purchase
    ]
];
