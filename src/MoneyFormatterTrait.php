<?php

namespace Cknow\Money;

use Cknow\Money\Formatters\CurrencySymbolMoneyFormatter;
use InvalidArgumentException;
use Money\Currencies;
use Money\Currencies\BitcoinCurrencies;
use Money\Formatter\AggregateMoneyFormatter;
use Money\Formatter\BitcoinMoneyFormatter;
use Money\Formatter\DecimalMoneyFormatter;
use Money\Formatter\IntlLocalizedDecimalFormatter;
use Money\Formatter\IntlMoneyFormatter;
use Money\MoneyFormatter;
use NumberFormatter;

trait MoneyFormatterTrait
{
    /**
     * Format.
     *
     * @param  string|null  $locale
     * @param  int  $style
     * @return string
     *
     * @throws \InvalidArgumentException
     */
    public function format($locale = null, Currencies $currencies = null, $style = NumberFormatter::CURRENCY)
    {
        $defaultFormatter = config('money.defaultFormatter');

        if (is_null($defaultFormatter)) {
            return $this->formatByIntl($locale, $currencies, $style);
        }

        $formatter = null;

        if (is_string($defaultFormatter)) {
            $formatter = app($defaultFormatter);
        }

        if (is_array($defaultFormatter) && count($defaultFormatter) === 2) {
            $formatter = app($defaultFormatter[0], $defaultFormatter[1]);
        }

        if ($formatter instanceof MoneyFormatter) {
            return $this->formatByFormatter($formatter);
        }

        throw new InvalidArgumentException(sprintf('Invalid default formatter %s', json_encode($defaultFormatter)));
    }

    /**
     * Format by aggregate.
     *
     * @param  MoneyFormatter[]  $formatters
     * @return string
     */
    public function formatByAggregate(array $formatters)
    {
        $formatter = new AggregateMoneyFormatter($formatters);

        return $this->formatByFormatter($formatter);
    }

    /**
     * Format by bitcoin.
     *
     * @param  int  $fractionDigits
     * @return string
     */
    public function formatByBitcoin($fractionDigits = 2, Currencies $currencies = null)
    {
        $formatter = new BitcoinMoneyFormatter($fractionDigits, $currencies ?: new BitcoinCurrencies());

        return $this->formatByFormatter($formatter);
    }

    /**
     * Format by currency symbol.
     *
     * @param  bool  $right
     * @param  string|null  $locale
     * @param  \Money\Currencies  $currencies
     * @return string
     */
    public function formatByCurrencySymbol($right = false, $locale = null, Currencies $currencies = null)
    {
        $formatter = new CurrencySymbolMoneyFormatter($right, $locale ?: static::getLocale(), $currencies ?: static::getCurrencies());

        return $this->formatByFormatter($formatter);
    }

    /**
     * Format by decimal.
     *
     * @return string
     */
    public function formatByDecimal(Currencies $currencies = null)
    {
        $formatter = new DecimalMoneyFormatter($currencies ?: static::getCurrencies());

        return $this->formatByFormatter($formatter);
    }

    /**
     * Format by intl.
     *
     * @param  string|null  $locale
     * @param  int  $style
     * @return string
     */
    public function formatByIntl($locale = null, Currencies $currencies = null, $style = NumberFormatter::CURRENCY)
    {
        $numberFormatter = new NumberFormatter($locale ?: static::getLocale(), $style);
        $formatter = new IntlMoneyFormatter($numberFormatter, $currencies ?: static::getCurrencies());

        return $this->formatByFormatter($formatter);
    }

    /**
     * Format by intl localized decimal.
     *
     * @param  string|null  $locale
     * @param  int  $style
     * @return string
     */
    public function formatByIntlLocalizedDecimal(
        $locale = null,
        Currencies $currencies = null,
        $style = NumberFormatter::CURRENCY
    ) {
        $numberFormatter = new NumberFormatter($locale ?: static::getLocale(), $style);
        $formatter = new IntlLocalizedDecimalFormatter($numberFormatter, $currencies ?: static::getCurrencies());

        return $this->formatByFormatter($formatter);
    }

    /**
     * Format by formatter.
     *
     * @return string
     */
    public function formatByFormatter(MoneyFormatter $formatter)
    {
        return $formatter->format($this->money);
    }
}
