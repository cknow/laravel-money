<?php

namespace Cknow\Money;

use InvalidArgumentException;
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
     * @param  mixed  $value
     * @param  \Money\Currency|string|null  $currency
     * @param  iny  $bitCointDigits
     * @return \Cknow\Money\Money|null
     *
     * @throws \InvalidArgumentException
     */
    public static function parse($value, $currency = null, $bitCointDigits = 2)
    {
        if ($value instanceof Money) {
            return $value;
        }

        if ($value instanceof \Money\Money) {
            return static::fromMoney($value);
        }

        $currency = static::parseCurrency($currency);

        if (is_int($value) || filter_var($value, FILTER_VALIDATE_INT) !== false) {
            return new Money($value, $currency);
        }

        if (is_scalar($value)) {
            $locale = static::getLocale();
            $currencies = static::getCurrencies();

            try {
                return static::parseByAggregate($value, null, [
                    new IntlMoneyParser(new NumberFormatter($locale, NumberFormatter::CURRENCY), $currencies),
                    new IntlLocalizedDecimalParser(new NumberFormatter($locale, NumberFormatter::DECIMAL), $currencies),
                    new DecimalMoneyParser($currencies),
                    new BitcoinMoneyParser($bitCointDigits),
                ]);
            } catch (ParserException $e) {
                return static::parseByAggregate($value, $currency, [
                    new IntlMoneyParser(new NumberFormatter($locale, NumberFormatter::CURRENCY), $currencies),
                    new IntlLocalizedDecimalParser(new NumberFormatter($locale, NumberFormatter::DECIMAL), $currencies),
                    new DecimalMoneyParser($currencies),
                    new BitcoinMoneyParser($bitCointDigits),
                ]);
            }
        }

        throw new InvalidArgumentException(sprintf('Invalid value %s', json_encode($value)));
    }

    /**
     * Parse by aggregate.
     *
     * @param  string  $money
     * @param  \Money\Currency|string|null  $fallbackCurrency
     * @param  MoneyParser[]  $parsers
     * @return \Cknow\Money\Money
     */
    public static function parseByAggregate($money, $fallbackCurrency = null, array $parsers = [])
    {
        $parser = new AggregateMoneyParser($parsers);

        return static::parseByParser($parser, $money, $fallbackCurrency);
    }

    /**
     * Parse by bitcoin.
     *
     * @param  string  $money
     * @param  \Money\Currency|string|null  $fallbackCurrency
     * @param  int  $fractionDigits
     * @return \Cknow\Money\Money
     */
    public static function parseByBitcoin($money, $fallbackCurrency = null, $fractionDigits = 2)
    {
        $parser = new BitcoinMoneyParser($fractionDigits);

        return static::parseByParser($parser, $money, $fallbackCurrency);
    }

    /**
     * Parse by decimal.
     *
     * @param  string  $money
     * @param  \Money\Currency|string|null  $fallbackCurrency
     * @param  \Money\Currencies|null  $currencies
     * @return \Cknow\Money\Money
     */
    public static function parseByDecimal($money, $fallbackCurrency = null, Currencies $currencies = null)
    {
        $parser = new DecimalMoneyParser($currencies ?: static::getCurrencies());

        return static::parseByParser($parser, $money, $fallbackCurrency);
    }

    /**
     * Parse by intl.
     *
     * @param  string  $money
     * @param  \Money\Currency|string|null  $fallbackCurrency
     * @param  string|null  $locale
     * @param  \Money\Currencies|null  $currencies
     * @param  int  $style
     * @return \Cknow\Money\Money
     */
    public static function parseByIntl(
        $money,
        $fallbackCurrency = null,
        $locale = null,
        Currencies $currencies = null,
        $style = NumberFormatter::CURRENCY
    ) {
        $numberFormatter = new NumberFormatter($locale ?: static::getLocale(), $style);
        $parser = new IntlMoneyParser($numberFormatter, $currencies ?: static::getCurrencies());

        return static::parseByParser($parser, $money, $fallbackCurrency);
    }

    /**
     * Parse by intl localized decimal.
     *
     * @param  string  $money
     * @param  \Money\Currency|string|null  $fallbackCurrency
     * @param  string|null  $locale
     * @param  \Money\Currencies|null  $currencies
     * @param  int  $style
     * @return \Cknow\Money\Money
     */
    public static function parseByIntlLocalizedDecimal(
        $money,
        $fallbackCurrency = null,
        $locale = null,
        Currencies $currencies = null,
        $style = NumberFormatter::DECIMAL
    ) {
        $numberFormatter = new NumberFormatter($locale ?: static::getLocale(), $style);
        $parser = new IntlLocalizedDecimalParser($numberFormatter, $currencies ?: static::getCurrencies());

        return static::parseByParser($parser, $money, $fallbackCurrency);
    }

    /**
     * Parse by parser.
     *
     * @param  \Money\MoneyParser  $parser
     * @param  string  $money
     * @param  \Money\Currency|string|null  $fallbackCurrency
     * @return \Cknow\Money\Money
     */
    public static function parseByParser(MoneyParser $parser, $money, $fallbackCurrency = null)
    {
        $fallbackCurrency = static::parseCurrency($fallbackCurrency);

        return static::convert($parser->parse((string) $money, $fallbackCurrency));
    }
}
