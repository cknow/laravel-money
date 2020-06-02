<?php

namespace Cknow\Money;

use Money\Currencies;
use Money\Currencies\AggregateCurrencies;
use Money\Currencies\BitcoinCurrencies;
use Money\Currencies\ISOCurrencies;

trait CurrenciesTrait
{
    /**
     * @var string
     */
    protected static $currency;

    /**
     * @var \Money\Currencies
     */
    protected static $currencies;

    /**
     * @var \Money\Currencies\AggregateCurrencies
     */
    protected static $allCurrencies;

    /**
     * Get default currency.
     *
     * @return string
     */
    public static function getDefaultCurrency()
    {
        if (!isset(static::$currency)) {
            static::setDefaultCurrency('USD');
        }

        return static::$currency;
    }

    /**
     * Set default currency.
     *
     * @param string $currency
     */
    public static function setDefaultCurrency($currency)
    {
        static::$currency = $currency;
    }

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
     * Get all currencies.
     *
     * @return \Money\Currencies\AggregateCurrencies
     */
    public static function getAllCurrencies()
    {
        if (!isset(static::$allCurrencies)) {
            static::setAllCurrencies(new AggregateCurrencies([
                new ISOCurrencies(),
                new BitcoinCurrencies()
            ]));
        }

        return static::$allCurrencies;
    }

    /**
     * Set all currencies.
     *
     * @param \Money\Currencies\AggregateCurrencies $allCurrencies
     */
    public static function setAllCurrencies(AggregateCurrencies $allCurrencies)
    {
        static::$allCurrencies = $allCurrencies;
    }
}
