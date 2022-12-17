<?php

namespace App\Exceptions;

use Exception;

class CoinNotFoundException extends Exception
{
    protected $coin;

    public function setCoin(string $coin): self
    {
        $this->coin = $coin;
        $this->message = "No query results for coin [{$coin}]";
        return $this;
    }
}
