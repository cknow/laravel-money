<?php

use ClickNow\Money\Money;
use ClickNow\Money\Currency;

if (! function_exists('money')) {
    /**
     * money.
     *
     * @param mixed  $amount
     * @param string $currency
     * @param bool   $convert
     *
     * @return \ClickNow\Money\Money
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
     * @return \ClickNow\Money\Currency
     */
    function currency($currency)
    {
        return new Currency($currency);
    }
}
