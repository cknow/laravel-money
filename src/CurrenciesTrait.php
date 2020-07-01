<?php

namespace Cknow\Money;

use InvalidArgumentException;
use Money\Currencies;
use Money\Currency;
use Money\Currencies\ISOCurrencies;
use Money\Currencies\BitcoinCurrencies;
use Money\Currencies\AggregateCurrencies;
use Money\Currencies\CurrencyList;

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
            static::setCurrencies([]);
        }

        return static::$currencies;
    }

    /**
     * Set currencies.
     *
     * @param \Money\Currencies|array|null $currencies
     */
    public static function setCurrencies($currencies)
    {
        static::$currencies = ($currencies instanceof Currencies)
            ? $currencies
            : static::makeCurrencies($currencies);
    }

    /**
     * Make currencies according to array derived from config or anywhere else.
     *
     * @param array|null $currenciesConfig
     *
     * @return \Money\Currencies
     */
    private static function makeCurrencies($currenciesConfig)
    {
        if (!$currenciesConfig || !is_array($currenciesConfig)) {
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
                $currenciesConfig['bitcoin'],
                new BitcoinCurrencies(),
                'Bitcoin'
            );
        }

        if ($currenciesConfig['custom'] ?? false) {
            $currenciesList[] = new CurrencyList($currenciesConfig['custom']);
        }

        return new AggregateCurrencies($currenciesList);
    }

    /**
     * Make currencies list according to array for specified source.
     *
     * @param array|string $config
     * @param \Money\Currencies $currencies
     * @param string $sourceName
     *
     * @throws \InvalidArgumentException
     *
     * @return \Money\Currencies
     */
    private static function makeCurrenciesForSource($config, Currencies $currencies, $sourceName)
    {
        if ($config === 'all') {
            return $currencies;
        }

        if (is_array($config)) {
            $lisCurrencies = [];

            foreach ($config as $index => $currencyCode) {
                $currency = new Currency($currencyCode);

                if (!$currencies->contains($currency)) {
                    throw new InvalidArgumentException(
                        sprintf('Unknown %s currency code: %s', $sourceName, $currencyCode)
                    );
                }

                $lisCurrencies[$currency->getCode()] = $currencies->subunitFor($currency);
            }

            return new CurrencyList($lisCurrencies);
        }

        throw new InvalidArgumentException(
            sprintf('%s config must be an array or \'all\'', $sourceName)
        );
    }
}
