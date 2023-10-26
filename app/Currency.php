<?php

namespace App;
class Currency
{
    private string $isoCode;

    public function __construct(string $isoCode)
    {
        $this->isoCode = $isoCode;
    }

    public function getIsoCode(): string
    {
        return $this->isoCode;
    }

    public function __toString(): string
    {
        return $this->isoCode;
    }
}