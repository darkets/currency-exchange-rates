<?php

namespace App\Collections;

use App\Currency;
use App\Result;

class ResultCollection
{
    private string $baseCurrency;
    private array $results = [];

    public function __construct(Currency $baseCurrency)
    {
        $this->baseCurrency = $baseCurrency;
    }

    public function add(Result $result): void
    {
        $this->results[] = $result;
    }

    public function getBaseCurrency(): string
    {
        return $this->baseCurrency;
    }

    public function sortDescending(): array
    {
        $sorted = &$this->results;

        usort($sorted, function ($a, $b) {
            return $b->getRate() <=> $a->getRate();
        });

        return $sorted;
    }
}