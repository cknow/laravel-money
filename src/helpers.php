<?php

if (! function_exists('currency')) {
    /**
     * currency.
     *
     * @param  \Money\Currency|string|null  $currency
     * @return \Money\Currency
     */
    function currency($currency = null)
    {
        return Cknow\Money\Money::parseCurrency($currency ?: Cknow\Money\Money::getDefaultCurrency());
    }
}

if (! function_exists('money')) {
    /**
     * money.
     *
     * @param  mixed  $amount
     * @param  \Money\Currency|string|null  $currency
     * @param  bool  $forceDecimals
     * @param  string|null  $locale
     * @param  \Money\Currencies|null  $currencies
     * @return \Cknow\Money\Money
     */
    function money($amount = null, $currency = null, $forceDecimals = false, $locale = null, $currencies = null)
    {
        return new Cknow\Money\Money($amount, $currency, $forceDecimals, $locale, $currencies);
    }
}

if (! function_exists('money_min')) {
    /**
     * money min.
     *
     * @param  \Cknow\Money\Money|\Money\Money  $first
     * @param  \Cknow\Money\Money|\Money\Money  ...$collection
     * @return \Cknow\Money\Money
     */
    function money_min($first, ...$collection)
    {
        return Cknow\Money\Money::min($first, ...$collection);
    }
}

if (! function_exists('money_max')) {
    /**
     * money max.
     *
     * @param  \Cknow\Money\Money|\Money\Money  $first
     * @param  \Cknow\Money\Money|\Money\Money  ...$collection
     * @return \Cknow\Money\Money
     */
    function money_max($first, ...$collection)
    {
        return Cknow\Money\Money::max($first, ...$collection);
    }
}

if (! function_exists('money_avg')) {
    /**
     * money avg.
     *
     * @param  \Cknow\Money\Money|\Money\Money  $first
     * @param  \Cknow\Money\Money|\Money\Money  ...$collection
     * @return \Cknow\Money\Money
     */
    function money_avg($first, ...$collection)
    {
        return Cknow\Money\Money::avg($first, ...$collection);
    }
}

if (! function_exists('money_sum')) {
    /**
     * money sum.
     *
     * @param  \Cknow\Money\Money|\Money\Money  $first
     * @param  \Cknow\Money\Money|\Money\Money  ...$collection
     * @return \Cknow\Money\Money
     */
    function money_sum($first, ...$collection)
    {
        return Cknow\Money\Money::sum($first, ...$collection);
    }
}

if (! function_exists('money_parse')) {
    /**
     * money parse.
     *
     * @param  mixed  $value
     * @param  \Money\Currency|string|null  $currency
     * @param  bool  $forceDecimals
     * @param  string|null  $locale
     * @param  \Money\Currencies|null  $currencies
     * @param  int|null  $bitCointDigits
     * @param  bool  $convert
     * @return \Cknow\Money\Money|\Money\Money
     */
    function money_parse(
        $value,
        $currency = null,
        $forceDecimals = false,
        $locale = null,
        $currencies = null,
        $bitCointDigits = null,
        $convert = true
    ) {
        return Cknow\Money\Money::parse(
            $value,
            $currency,
            $forceDecimals,
            $locale,
            $currencies,
            $bitCointDigits,
            $convert
        );
    }
}

if (! function_exists('money_parse_by_bitcoin')) {
    /**
     * money parse by bitcoin.
     *
     * @param  string  $money
     * @param  \Money\Currency|string|null  $fallbackCurrency
     * @param  int|null  $fractionDigits
     * @param  bool  $convert
     * @return \Cknow\Money\Money|\Money\Money
     */
    function money_parse_by_bitcoin(
        $money,
        $fallbackCurrency = null,
        $fractionDigits = null,
        $convert = true
    ) {
        return Cknow\Money\Money::parseByBitcoin(
            $money,
            $fallbackCurrency,
            $fractionDigits,
            $convert
        );
    }
}

if (! function_exists('money_parse_by_decimal')) {
    /**
     * money parse by decimal.
     *
     * @param  string  $money
     * @param  \Money\Currency|string|null  $fallbackCurrency
     * @param  \Money\Currencies|null  $currencies
     * @param  bool  $convert
     * @return \Cknow\Money\Money|\Money\Money
     */
    function money_parse_by_decimal(
        $money,
        $fallbackCurrency = null,
        Money\Currencies $currencies = null,
        $convert = true
    ) {
        return Cknow\Money\Money::parseByDecimal(
            $money,
            $fallbackCurrency,
            $currencies,
            $convert
        );
    }
}

if (! function_exists('money_parse_by_intl')) {
    /**
     * money parse by intl.
     *
     * @param  string  $money
     * @param  \Money\Currency|string|null  $fallbackCurrency
     * @param  string|null  $locale
     * @param  \Money\Currencies|null  $currencies
     * @param  int|null  $style
     * @param  bool  $convert
     * @return \Cknow\Money\Money|\Money\Money
     */
    function money_parse_by_intl(
        $money,
        $fallbackCurrency = null,
        $locale = null,
        Money\Currencies $currencies = null,
        $style = null,
        $convert = true
    ) {
        return Cknow\Money\Money::parseByIntl(
            $money,
            $fallbackCurrency,
            $locale,
            $currencies,
            $style,
            $convert
        );
    }
}

if (! function_exists('money_parse_by_intl_localized_decimal')) {
    /**
     * money parse by intl localized decimal.
     *
     * @param  string  $money
     * @param  \Money\Currency|string|null  $fallbackCurrency
     * @param  string|null  $locale
     * @param  \Money\Currencies|null  $currencies
     * @param  int|null  $style
     * @param  bool  $convert
     * @return \Cknow\Money\Money|\Money\Money
     */
    function money_parse_by_intl_localized_decimal(
        $money,
        $fallbackCurrency = null,
        $locale = null,
        Money\Currencies $currencies = null,
        $style = null,
        $convert = true
    ) {
        return Cknow\Money\Money::parseByIntlLocalizedDecimal(
            $money,
            $fallbackCurrency,
            $locale,
            $currencies,
            $style,
            $convert
        );
    }
}
