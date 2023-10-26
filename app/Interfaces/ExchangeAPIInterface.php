<?php

namespace App\Interfaces;

use App\Currency;
use App\Result;

interface ExchangeAPIInterface
{
    public function fetchExchangeData(Currency $baseCurrency, Currency $currency): ?Result;
}