<?php

namespace Cknow\Money;

use Money\Currencies;
use Money\Currencies\ISOCurrencies;

trait CurrenciesTrait
{
    /**
     * @var \Money\Currencies
     */
    protected static $currencies;

    /**
     * @var string
     */
    protected static $currency;

    /**
     * Get currencies.
     *
     * @return \Money\Currencies
     */
    public static function getCurrencies()
    {
        if (!isset(static::$currencies)) {
            static::setCurrencies(new ISOCurrencies());
        }

        return static::$currencies;
    }

    /**
     * Set currencies.
     *
     * @param \Money\Currencies $currencies
     */
    public static function setCurrencies(Currencies $currencies)
    {
        static::$currencies = $currencies;
    }

    /**
     * Get currency.
     *
     * @return string
     */
    public static function getCurrency()
    {
        if (!isset(static::$currency)) {
            static::setCurrency('USD');
        }

        return static::$currency;
    }

    /**
     * Set currency.
     *
     * @param string $currency
     */
    public static function setCurrency($currency)
    {
        static::$currency = $currency;
    }
}
