<?php
declare(strict_types=1);

namespace App;

use App\Collections\ExchangeCollection;
use App\Collections\ResultCollection;
use App\Interfaces\ExchangeAPIInterface;

class Application
{
    private ExchangeCollection $exchanges;

    public function __construct()
    {
        $this->exchanges = new ExchangeCollection();
    }

    public function run()
    {
        while (true) {
            echo "Enter base amount and currency (<amount> <currency>): ";
            $input = explode(' ', readline());
            $amount = $input[0];

            if (empty($amount)) {
                echo 'No base amount was entered' . PHP_EOL;
                continue;
            }

            echo 'Enter target currency: ';
            $currency = new Currency(strtoupper(readline()));

            if (empty($currency)) {
                echo 'No target currency was entered' . PHP_EOL;
                continue;
            }

            $baseCurrency = new Currency(strtoupper($input[1] ?? ''));

            if (empty($baseCurrency)) {
                echo 'No base currency was entered' . PHP_EOL;
                continue;
            }

            $results = $this->getResults($baseCurrency, $currency);
            $this->displayResults($results, (int)$amount * 100);
        }
    }

    private function getResults(Currency $baseCurrency, Currency $currency): ResultCollection
    {
        $results = new ResultCollection($baseCurrency);

        foreach ($this->exchanges->get() as $exchange) {
            /** @var ExchangeAPIInterface $exchange */
            $result = $exchange->fetchExchangeData($baseCurrency, $currency);
            if (!$result) {
                continue;
            }
            $results->add($result);
        }

        return $results;
    }

    private function displayResults(ResultCollection $results, int $amount): void
    {
        echo "Base Currency: {$results->getBaseCurrency()}" . PHP_EOL;

        $recommendedExchange = true;

        foreach ($results->sortDescending() as $result) {
            /** @var Result $result */

            if ($recommendedExchange) {
                echo "\033[1;32m";
                echo '-------RECOMMENDED---------' . PHP_EOL;
            } else {
                echo "\033[0m";
            }

            $convertedAmount = $amount / 100 * $result->getRate();

            echo "Currency: {$result->getCurrency()}" . PHP_EOL;
            echo "Rate: {$result->getRate()}" . PHP_EOL;
            echo "Conversion Amount: $convertedAmount" . PHP_EOL;
            echo "Source: {$result->getSource()}" . PHP_EOL;
            echo '----------------' . PHP_EOL;

            $recommendedExchange = false;
        }

        echo "\033[0m";
    }
}
