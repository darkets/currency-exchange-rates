<?php

namespace App\Collections;

use App\Api\FastForexAPI;
use App\Api\FreeCurrencyAPI;

class ExchangeCollection
{
    private array $exchanges;

    public function __construct()
    {
        $this->exchanges = [
            new FastForexAPI(),
            new FreeCurrencyAPI(),
        ];
    }

    public function get(): array
    {
        return $this->exchanges;
    }
}
