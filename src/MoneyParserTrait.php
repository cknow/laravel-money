<?php

namespace Cknow\Money;

use Money\Currencies;
use Money\MoneyParser;
use Money\Parser\DecimalMoneyParser;
use Money\Parser\IntlMoneyParser;
use NumberFormatter;

trait MoneyParserTrait
{
    /**
     * Parse.
     *
     * @param string            $money
     * @param string|null       $forceCurrency
     * @param string|null       $locale
     * @param \Money\Currencies $currencies
     *
     * @return \Cknow\Money\Money
     */
    public static function parse($money, $forceCurrency = null, $locale = null, Currencies $currencies = null)
    {
        $numberFormatter = new NumberFormatter($locale ?: static::getLocale(), NumberFormatter::CURRENCY);
        $parser = new IntlMoneyParser($numberFormatter, $currencies ?: static::getCurrencies());

        return self::parseByParser($parser, $money, $forceCurrency);
    }

    /**
     * Parse by decimal.
     *
     * @param string            $money
     * @param string            $forceCurrency
     * @param \Money\Currencies $currencies
     *
     * @return \Cknow\Money\Money
     */
    public static function parseByDecimal($money, $forceCurrency, Currencies $currencies = null)
    {
        $parser = new DecimalMoneyParser($currencies ?: static::getCurrencies());

        return self::parseByParser($parser, $money, $forceCurrency);
    }

    /**
     * Parse by parser.
     *
     * @param \Money\MoneyParser $parser
     * @param string             $money
     * @param string|null        $forceCurrency
     *
     * @return \Cknow\Money\Money
     */
    public static function parseByParser(MoneyParser $parser, $money, $forceCurrency = null)
    {
        return static::convert($parser->parse($money, $forceCurrency));
    }
}
