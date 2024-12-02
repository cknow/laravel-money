<?php

namespace Cknow\Money\Currencies;

use ArrayIterator;
use Money\Currencies;
use Money\Currency;
use Money\Exception\UnknownCurrencyException;
use Traversable;

class ISOCurrencies implements Currencies
{
    /**
     * @var array
     */
    private static $currencies;

    public function contains(Currency $currency): bool
    {
        return isset($this->getCurrencies()[$currency->getCode()]);
    }

    public function subunitFor(Currency $currency): int
    {
        if (! $this->contains($currency)) {
            throw new UnknownCurrencyException('Cannot find ISO currency '.$currency->getCode());
        }

        return $this->getCurrencies()[$currency->getCode()]['minorUnit'];
    }

    /**
     * Returns the numeric code for a currency.
     *
     * @throws UnknownCurrencyException If currency is not available in the current context.
     */
    public function numericCodeFor(Currency $currency): int
    {
        if (! $this->contains($currency)) {
            throw new UnknownCurrencyException('Cannot find ISO currency '.$currency->getCode());
        }

        return $this->getCurrencies()[$currency->getCode()]['numericCode'];
    }

    #[\ReturnTypeWillChange]
    public function getIterator(): Traversable
    {
        return new ArrayIterator(
            array_map(
                static function ($code) {
                    return new Currency($code);
                },
                array_keys($this->getCurrencies())
            )
        );
    }

    /**
     * Returns a map of known currencies indexed by code.
     *
     * @return array
     */
    public function getCurrencies()
    {
        if (self::$currencies === null) {
            self::$currencies = $this->loadCurrencies();
        }

        return self::$currencies;
    }

    /**
     * @return array
     */
    protected function loadCurrencies()
    {
        $file = config('money.isoCurrenciesPath');

        if (file_exists($file)) {
            return require $file;
        }

        throw new \RuntimeException('Failed to load currency ISO codes.');
    }
}
