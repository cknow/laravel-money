<?php

if (!function_exists('money')) {
    /**
     * money.
     *
     * @param mixed  $amount
     * @param string $currency
     *
     * @return \Cknow\Money\Money
     */
    function money($amount, $currency = 'BRL')
    {
        return new Cknow\Money\Money($amount, new Money\Currency($currency));
    }
}

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
