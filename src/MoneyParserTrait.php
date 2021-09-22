<?php

namespace Cknow\Money;

use InvalidArgumentException;
use Money\Currency;
use Money\Currencies;
use Money\Exception\ParserException;
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
     * Convert the given value into an instance of Money.
     *
     * @param mixed                       $value
     * @param \Money\Currency|string|null $currency
     * @param iny                         $bitCointDigits
     *
     * @return \Cknow\Money\Money|null
     */
    public static function parse($value, $currency = null, $bitCointDigits = 2)
    {
        if ($value instanceof Money) {
            return $value;
        }

        if ($value instanceof \Money\Money) {
            return static::fromMoney($value);
        }

        if (is_string($currency)) {
            $currency = new Currency($currency);
        }

        if (is_string($value) && filter_var($value, FILTER_VALIDATE_FLOAT) === false) {
            $locale = static::getLocale();
            $currencies = static::getCurrencies();

            try {
                return static::parseByAggregate($value, null, [
                    new IntlMoneyParser(new NumberFormatter($locale, NumberFormatter::CURRENCY), $currencies),
                    new BitcoinMoneyParser($bitCointDigits),
                ]);
            } catch (ParserException $e) {
                try {
                    return static::parseByIntlLocalizedDecimal($value, $currency, $locale, $currencies);
                } catch (ParserException $e) {
                    throw new ParserException(sprintf('Unable to parse: %s', $value));
                }
            }
        }

        if (is_string($value)) {
            return new Money($value, $currency);
        }

        if (is_int($value) || is_float($value)) {
            return static::parseByDecimal((string) $value, $currency);
        }

        throw new InvalidArgumentException(sprintf('Invalid value: %s', json_encode($value)));
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

        return static::parseByParser($parser, $money, $forceCurrency);
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

        return static::parseByParser($parser, $money, $forceCurrency);
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

        return static::parseByParser($parser, $money, $forceCurrency);
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

        return static::parseByParser($parser, $money, $forceCurrency);
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

        return static::parseByParser($parser, $money, $forceCurrency);
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
        if (is_string($forceCurrency)) {
            $forceCurrency = new Currency($forceCurrency);
        }

        return static::convert($parser->parse($money, $forceCurrency));
    }
}
