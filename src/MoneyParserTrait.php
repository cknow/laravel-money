<?php

namespace Cknow\Money;

use Money\Currencies;
use Money\MoneyParser;
use Money\Parser\AggregateMoneyParser;
use Money\Parser\BitcoinMoneyParser;
use Money\Parser\DecimalMoneyParser;
use Money\Parser\IntlLocalizedDecimalParser;
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
        return self::parseByIntl($money, $forceCurrency, $locale, $currencies);
    }

    /**
     * Parse by aggregate.
     *
     * @param string        $money
     * @param string|null   $forceCurrency
     * @param MoneyParser[] $parsers
     *
     * @return \Cknow\Money\Money
     */
    public static function parseByAggregate($money, $forceCurrency = null, array $parsers = [])
    {
        $parser = new AggregateMoneyParser($parsers);

        return self::parseByParser($parser, $money, $forceCurrency);
    }

    /**
     * Parse by bitcoin.
     *
     * @param string      $money
     * @param string|null $forceCurrency
     * @param int         $fractionDigits
     *
     * @return \Cknow\Money\Money
     */
    public static function parseByBitcoin($money, $forceCurrency = null, $fractionDigits = 2)
    {
        $parser = new BitcoinMoneyParser($fractionDigits);

        return self::parseByParser($parser, $money, $forceCurrency);
    }

    /**
     * Parse by decimal.
     *
     * @param string            $money
     * @param string|null       $forceCurrency
     * @param \Money\Currencies $currencies
     *
     * @return \Cknow\Money\Money
     */
    public static function parseByDecimal($money, $forceCurrency = null, Currencies $currencies = null)
    {
        $parser = new DecimalMoneyParser($currencies ?: static::getCurrencies());

        return self::parseByParser($parser, $money, $forceCurrency);
    }

    /**
     * Parse by intl.
     *
     * @param string            $money
     * @param string|null       $forceCurrency
     * @param string|null       $locale
     * @param \Money\Currencies $currencies
     * @param int               $style
     *
     * @return \Cknow\Money\Money
     */
    public static function parseByIntl(
        $money,
        $forceCurrency = null,
        $locale = null,
        Currencies $currencies = null,
        $style = NumberFormatter::CURRENCY
    ) {
        $numberFormatter = new NumberFormatter($locale ?: static::getLocale(), $style);
        $parser = new IntlMoneyParser($numberFormatter, $currencies ?: static::getCurrencies());

        return self::parseByParser($parser, $money, $forceCurrency);
    }

    /**
     * Parse by intl localized decimal.
     *
     * @param string            $money
     * @param string            $forceCurrency
     * @param string|null       $locale
     * @param \Money\Currencies $currencies
     * @param int               $style
     *
     * @return \Cknow\Money\Money
     */
    public static function parseByIntlLocalizedDecimal(
        $money,
        $forceCurrency,
        $locale = null,
        Currencies $currencies = null,
        $style = NumberFormatter::DECIMAL
    ) {
        $numberFormatter = new NumberFormatter($locale ?: static::getLocale(), $style);
        $parser = new IntlLocalizedDecimalParser($numberFormatter, $currencies ?: static::getCurrencies());

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
