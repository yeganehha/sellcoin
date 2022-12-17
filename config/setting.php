<?php

return [
    'coins' => [
        'list' => [
            'cache_time' => 5 * 60 // time of caching list of coins per each exchange platform
        ],
        'cache_time' => 60 // time of caching information of special coin
    ],
    'purchase' => [
        'wait_for_confirm' => 5 // minute wait before auto cancel  purchase
    ]
];
