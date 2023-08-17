<?php

namespace Cknow\Money\Formatters;

use Cknow\Money\Money;
use Money\Currencies;
use Money\Formatter\DecimalMoneyFormatter;
use Money\MoneyFormatter;
use NumberFormatter;

class CurrencySymbolMoneyFormatter implements MoneyFormatter
{
    /**
     * @var bool
     */
    protected $right;

    /**
     * @var string
     */
    protected $locale;

    /**
     * @var \Money\Currencies
     */
    protected $currencies;

    /**
     * Instantiate the class.
     *
     * @param  bool  $right
     * @param  string|null  $locale
     */
    public function __construct($right = false, $locale = null, Currencies $currencies = null)
    {
        $this->right = $right;
        $this->locale = $locale ?: Money::getLocale();
        $this->currencies = $currencies ?: Money::getCurrencies();
    }

    /**
     * Formats a Money object as string.
     */
    public function format(\Money\Money $money): string
    {
        $numberFormatter = new NumberFormatter($this->locale, NumberFormatter::CURRENCY);
        $symbol = $numberFormatter->getSymbol(NumberFormatter::CURRENCY_SYMBOL);

        $formatter = new DecimalMoneyFormatter($this->currencies);
        $value = $formatter->format($money);

        return $this->right ? $value.$symbol : $symbol.$value;
    }
}
