<?php

use \Clicknow\Money\Money;
use \Clicknow\Money\Currency;

if (! function_exists('money')) {

    /**
     * money.
     *
     * @param mixed  $amount
     * @param string $currency
     * @param bool   $convert
     *
     * @return \Clicknow\Money\Money
     */
    function money($amount, $currency = 'BRL', $convert = false)
    {
        return new Money($amount, currency($currency), $convert);
    }
}

if (! function_exists('currency')) {

    /**
     * currency.
     *
     * @param string $currency
     *
     * @return \Clicknow\Money\Currency
     */
    function currency($currency)
    {
        return new Currency($currency);
    }
}
