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
     * @param  bool  $forceDecimals
     * @param  string|null  $locale
     * @param  \Money\Currencies|null  $currencies
     * @param  int|null  $bitCointDigits
     * @param  bool  $convert
     * @return \Cknow\Money\Money|\Money\Money
     *
     * @throws \InvalidArgumentException
     */
    public static function parse(
        $value,
        $currency = null,
        $forceDecimals = false,
        $locale = null,
        $currencies = null,
        $bitCointDigits = null,
        $convert = true
    ) {
        $value = is_null($value) ? (int) $value : $value;

        if ($value instanceof Money) {
            return $convert ? $value : $value->getMoney();
        }

        if ($value instanceof \Money\Money) {
            return $convert ? static::fromMoney($value) : $value;
        }

        if (! is_scalar($value)) {
            throw new InvalidArgumentException(sprintf('Invalid value %s', json_encode($value)));
        }

        if (
            (is_int($value) || (filter_var($value, FILTER_VALIDATE_INT) !== false && ! is_float($value)))
            && $forceDecimals
        ) {
            $value = sprintf('%.14F', $value);
        }

        $currency = static::parseCurrency($currency ?: static::getDefaultCurrency());

        if (is_int($value) || (filter_var($value, FILTER_VALIDATE_INT) !== false && ! is_float($value))) {
            return $convert
                ? new Money($value, $currency)
                : new \Money\Money($value, $currency);
        }

        $currencies = $currencies ?: static::getCurrencies();

        if (is_float($value) || filter_var($value, FILTER_VALIDATE_FLOAT)) {
            return static::parseByDecimal($value, $currency, $currencies, $convert);
        }

        $locale = $locale ?: static::getLocale();
        $bitCointDigits = $bitCointDigits ?? 2;

        try {
            $parsers = [
                new IntlMoneyParser(new NumberFormatter($locale, NumberFormatter::CURRENCY), $currencies),
                new IntlLocalizedDecimalParser(new NumberFormatter($locale, NumberFormatter::CURRENCY), $currencies),
                new DecimalMoneyParser($currencies),
                new BitcoinMoneyParser($bitCointDigits),
            ];

            return static::parseByAggregate($value, null, $parsers, $convert);
        } catch (ParserException $e) {
            try {
                return static::parseByAggregate($value, $currency, $parsers, $convert);
            } catch (ParserException $e) {
                $parsers = [
                    new IntlMoneyParser(new NumberFormatter($locale, NumberFormatter::DECIMAL), $currencies),
                    new IntlLocalizedDecimalParser(new NumberFormatter($locale, NumberFormatter::DECIMAL), $currencies),
                    new DecimalMoneyParser($currencies),
                    new BitcoinMoneyParser($bitCointDigits),
                ];

                try {
                    return static::parseByAggregate($value, null, $parsers, $convert);
                } catch (ParserException $e) {
                    return static::parseByAggregate($value, $currency, $parsers, $convert);
                }
            }
        }
    }

    /**
     * Parse by aggregate.
     *
     * @param  string  $money
     * @param  \Money\Currency|string|null  $fallbackCurrency
     * @param  MoneyParser[]  $parsers
     * @param  bool  $convert
     * @return \Cknow\Money\Money|\Money\Money
     */
    public static function parseByAggregate(
        $money,
        $fallbackCurrency = null,
        array $parsers = [],
        $convert = true
    ) {
        $parser = new AggregateMoneyParser($parsers);

        return static::parseByParser($parser, $money, $fallbackCurrency, $convert);
    }

    /**
     * Parse by bitcoin.
     *
     * @param  string  $money
     * @param  \Money\Currency|string|null  $fallbackCurrency
     * @param  int|null  $fractionDigits
     * @param  bool  $convert
     * @return \Cknow\Money\Money|\Money\Money
     */
    public static function parseByBitcoin(
        $money,
        $fallbackCurrency = null,
        $fractionDigits = null,
        $convert = true
    ) {
        $parser = new BitcoinMoneyParser($fractionDigits ?? 2);

        return static::parseByParser($parser, $money, $fallbackCurrency, $convert);
    }

    /**
     * Parse by decimal.
     *
     * @param  string  $money
     * @param  \Money\Currency|string|null  $fallbackCurrency
     * @param  bool  $convert
     * @return \Cknow\Money\Money|\Money\Money
     */
    public static function parseByDecimal(
        $money,
        $fallbackCurrency = null,
        Currencies $currencies = null,
        $convert = true
    ) {
        $parser = new DecimalMoneyParser($currencies ?: static::getCurrencies());

        return static::parseByParser($parser, $money, $fallbackCurrency, $convert);
    }

    /**
     * Parse by intl.
     *
     * @param  string  $money
     * @param  \Money\Currency|string|null  $fallbackCurrency
     * @param  string|null  $locale
     * @param  int|null  $style
     * @param  bool  $convert
     * @return \Cknow\Money\Money|\Money\Money
     */
    public static function parseByIntl(
        $money,
        $fallbackCurrency = null,
        $locale = null,
        Currencies $currencies = null,
        $style = null,
        $convert = true
    ) {
        $numberFormatter = new NumberFormatter(
            $locale ?: static::getLocale(),
            $style ?: NumberFormatter::CURRENCY || NumberFormatter::DECIMAL
        );

        $parser = new IntlMoneyParser($numberFormatter, $currencies ?: static::getCurrencies());

        return static::parseByParser($parser, $money, $fallbackCurrency, $convert);
    }

    /**
     * Parse by intl localized decimal.
     *
     * @param  string  $money
     * @param  \Money\Currency|string|null  $fallbackCurrency
     * @param  string|null  $locale
     * @param  int|null  $style
     * @param  bool  $convert
     * @return \Cknow\Money\Money|\Money\Money
     */
    public static function parseByIntlLocalizedDecimal(
        $money,
        $fallbackCurrency = null,
        $locale = null,
        Currencies $currencies = null,
        $style = null,
        $convert = true
    ) {
        $numberFormatter = new NumberFormatter(
            $locale ?: static::getLocale(),
            $style ?: NumberFormatter::CURRENCY || NumberFormatter::DECIMAL
        );

        $parser = new IntlLocalizedDecimalParser($numberFormatter, $currencies ?: static::getCurrencies());

        return static::parseByParser($parser, $money, $fallbackCurrency, $convert);
    }

    /**
     * Parse by parser.
     *
     * @param  string  $money
     * @param  \Money\Currency|string|null  $fallbackCurrency
     * @param  bool  $convert
     * @return \Cknow\Money\Money|\Money\Money
     */
    public static function parseByParser(
        MoneyParser $parser,
        $money,
        $fallbackCurrency = null,
        $convert = true
    ) {
        $fallbackCurrency = static::parseCurrency($fallbackCurrency);
        $originalMoney = $parser->parse((string) $money, $fallbackCurrency);

        return $convert ? static::convert($originalMoney) : $originalMoney;
    }
}
