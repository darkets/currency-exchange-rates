<?php

namespace App;

class Result
{
    private Currency $currency;
    private float $rate;
    private string $source;

    public function __construct(Currency $currency, float $rate, string $source)
    {
        $this->currency = $currency;
        $this->rate = $rate;
        $this->source = $source;
    }

    public function getCurrency(): Currency
    {
        return $this->currency;
    }

    public function getRate(): float
    {
        return $this->rate;
    }

    public function getSource(): string
    {
        return parse_url($this->source)['host'];
    }
}