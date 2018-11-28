<?php

namespace Cknow\Money;

use Money\Currencies;
use Money\Formatter\DecimalMoneyFormatter;
use Money\Formatter\IntlMoneyFormatter;
use Money\MoneyFormatter;
use NumberFormatter;

trait MoneyFormatterTrait
{
    /**
     * Format.
     *
     * @param string|null       $locale
     * @param \Money\Currencies $currencies
     * @param int               $style
     *
     * @return string
     */
    public function format($locale = null, Currencies $currencies = null, $style = NumberFormatter::CURRENCY)
    {
        $numberFormatter = new NumberFormatter($locale ?: static::getLocale(), $style);
        $formatter = new IntlMoneyFormatter($numberFormatter, $currencies ?: static::getCurrencies());

        return $this->formatByFormatter($formatter);
    }

    /**
     * Format by decimal.
     *
     * @param \Money\Currencies $currencies
     *
     * @return string
     */
    public function formatByDecimal(Currencies $currencies = null)
    {
        $formatter = new DecimalMoneyFormatter($currencies ?: static::getCurrencies());

        return $this->formatByFormatter($formatter);
    }

    /**
     * Format by formatter.
     *
     * @param \Money\MoneyFormatter $formatter
     *
     * @return string
     */
    public function formatByFormatter(MoneyFormatter $formatter)
    {
        return $formatter->format($this->money);
    }
}
