<?php

namespace Cknow\Money;

use Money\Currencies;
use Money\Currencies\ISOCurrencies;
use Money\Currencies\BitcoinCurrencies;
use Money\Currencies\AggregateCurrencies;
use Money\Currencies\CurrencyList;


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
            static::setCurrencies([]);
        }

        return static::$currencies;
    }

    /**
     * Set currencies.
     *
     * @param \Money\Currencies|array $currencies
     */
    public static function setCurrencies($currencies)
    {
        if ($currencies instanceof Currencies) {
            static::$currencies = $currencies;
        } elseIf (is_array($currencies)) {
            static::$currencies = static::makeCurrencies($currencies);
        } else {
            throw new \InvalidArgumentException(
                '$currencies must be an array or a \Money\Currencies object');
        }
    }

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
     * Make currencies list according to array for specified source.
     *
     * @param array|string $config
     * @param Currencies $allCurrencies
     * @param string $sourceName
     * @return \Money\Currencies
     */
    private static function makeCurrenciesForSource($config, $allCurrencies, $sourceName)
    {
        if ('all' === $config) {
            return $allCurrencies;
        }

        if (is_array($config)) {
            $currencies = [];
            foreach ($config as $index => $currencyCode) {
                $currency = new Currency($currencyCode);
                if ($allCurrencies->contains($currency)) {
                    $currencies[] = $currency;
                } else {
                    throw new \InvalidArgumentException(
                        "Unknown $sourceName currency code: $currencyCode");
                }
            }
            return new CurrencyList($currencies);
        }

        throw new \InvalidArgumentException("$sourceName config must be an array or 'all'");
    }

    /**
     * Make currencies according to array derived from config or anywhere else.
     *
     * @param array $currenciesConfig
     * @return \Money\Currencies
     */
    private static function makeCurrencies(array $currenciesConfig)
    {
        if (!$currenciesConfig) {
            // for backward compatibility
            return new ISOCurrencies();
        }

        $currenciesList = [];

        if ($currenciesConfig['iso'] ?? false) {
            $currenciesList[] = static::makeCurrenciesForSource(
                $currenciesConfig['iso'],
                new ISOCurrencies(), 
                'ISO'
            );
        }

        if ($currenciesConfig['bitcoin'] ?? false) {
            $currenciesList[] = static::makeCurrenciesForSource(
                $currenciesConfig['bitcoin'] ?? [],
                new BitcoinCurrencies(),
                'Bitcoin'
            );
        }

        if ($currenciesConfig['custom'] ?? false) {
            $currenciesList[] = new CurrencyList($currenciesConfig['custom']);
        }

        return new AggregateCurrencies([$currenciesList]);
    }
}
