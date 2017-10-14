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
    function money($amount, $currency = 'BRL')
    {
        return new Cknow\Money\Money($amount, new Money\Currency($currency));
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
