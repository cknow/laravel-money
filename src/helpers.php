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

if (!function_exists('money_min')) {
    /**
     * money min.
     *
     * @param \Cknow\Money\Money $first
     * @param \Cknow\Money\Money ...$collection
     *
     * @return \Cknow\Money\Money
     */
    function money_min(Cknow\Money\Money $first, Cknow\Money\Money ...$collection)
    {
        return Cknow\Money\Money::min($first, ...$collection);
    }
}

if (!function_exists('money_max')) {
    /**
     * money max.
     *
     * @param \Cknow\Money\Money $first
     * @param \Cknow\Money\Money ...$collection
     *
     * @return \Cknow\Money\Money
     */
    function money_max(Cknow\Money\Money $first, Cknow\Money\Money ...$collection)
    {
        return Cknow\Money\Money::max($first, ...$collection);
    }
}

if (!function_exists('money_avg')) {
    /**
     * money avg.
     *
     * @param \Cknow\Money\Money $first
     * @param \Cknow\Money\Money ...$collection
     *
     * @return \Cknow\Money\Money
     */
    function money_avg(Cknow\Money\Money $first, Cknow\Money\Money ...$collection)
    {
        return Cknow\Money\Money::avg($first, ...$collection);
    }
}

if (!function_exists('money_sum')) {
    /**
     * money sum.
     *
     * @param \Cknow\Money\Money $first
     * @param \Cknow\Money\Money ...$collection
     *
     * @return \Cknow\Money\Money
     */
    function money_sum(Cknow\Money\Money $first, Cknow\Money\Money ...$collection)
    {
        return Cknow\Money\Money::sum($first, ...$collection);
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

if (!function_exists('money_parse_by_bitcoin')) {
    /**
     * money parse by bitcoin.
     *
     * @param string      $money
     * @param string|null $forceCurrency
     * @param int         $fractionDigits
     *
     * @return \Cknow\Money\Money
     */
    function money_parse_by_bitcoin($money, $forceCurrency = null, $fractionDigits = 2)
    {
        return Cknow\Money\Money::parseByBitcoin($money, $forceCurrency, $fractionDigits);
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

if (!function_exists('money_parse_by_intl')) {
    /**
     * money parse by intl.
     *
     * @param string            $money
     * @param string|null       $forceCurrency
     * @param string|null       $locale
     * @param \Money\Currencies $currencies
     *
     * @return \Cknow\Money\Money
     */
    function money_parse_by_intl($money, $forceCurrency = null, $locale = null, Money\Currencies $currencies = null)
    {
        return Cknow\Money\Money::parseByIntl($money, $forceCurrency, $locale, $currencies);
    }
}

if (!function_exists('money_parse_by_intl_localized_decimal')) {
    /**
     * money parse by intl localized decimal.
     *
     * @param string            $money
     * @param string            $forceCurrency
     * @param string|null       $locale
     * @param \Money\Currencies $currencies
     *
     * @return \Cknow\Money\Money
     */
    function money_parse_by_intl_localized_decimal(
        $money,
        $forceCurrency,
        $locale = null,
        Money\Currencies $currencies = null
    ) {
        return Cknow\Money\Money::parseByIntlLocalizedDecimal($money, $forceCurrency, $locale, $currencies);
    }
}
