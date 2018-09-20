<?php

if (!function_exists('currency')) {
    /**
     * currency.
     *
     * @param string $currency
     *
     * @return \Money\Currency
     */
    function currency($currency)
    {
        return new Money\Currency($currency);
    }
}

if (!function_exists('money')) {
    /**
     * money.
     *
     * @param int|string $amount
     * @param string     $currency
     *
     * @return \Cknow\Money\Money
     */
    function money($amount, $currency = null)
    {
        return new Cknow\Money\Money($amount, new Money\Currency($currency ?: Cknow\Money\Money::getCurrency()));
    }
}

if (!function_exists('money_parse')) {
    /**
     * money parse.
     *
     * @param string            $money
     * @param string|null       $forceCurrency
     * @param string|null       $locale
     * @param \Money\Currencies $currencies
     *
     * @return \Cknow\Money\Money
     */
    function money_parse($money, $forceCurrency = null, $locale = null, Money\Currencies $currencies = null)
    {
        return Cknow\Money\Money::parse($money, $forceCurrency, $locale, $currencies);
    }
}

if (!function_exists('money_parse_by_decimal')) {
    /**
     * money parse by decimal.
     *
     * @param string            $money
     * @param string|null       $forceCurrency
     * @param \Money\Currencies $currencies
     *
     * @return \Cknow\Money\Money
     */
    function money_parse_by_decimal($money, $forceCurrency = null, Money\Currencies $currencies = null)
    {
        return Cknow\Money\Money::parseByDecimal($money, $forceCurrency, $currencies);
    }
}
