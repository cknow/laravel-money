<?php

namespace Cknow\Money;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Support\Facades\Config;
use InvalidArgumentException;
use Money\Currencies\AggregateCurrencies;
use Money\Currencies\BitcoinCurrencies;
use Money\Currencies\ISOCurrencies;
use Money\Currency;
use Money\Exception\ParserException;
use Money\Formatter\DecimalMoneyFormatter;
use Money\Money as BaseMoney;
use Money\Parser\AggregateMoneyParser;
use Money\Parser\BitcoinMoneyParser;
use Money\Parser\DecimalMoneyParser;
use Money\Parser\IntlLocalizedDecimalParser;
use Money\Parser\IntlMoneyParser;
use NumberFormatter;

/**
 * Cast model attributes into Money.
 *
 */
class MoneyCast implements CastsAttributes
{
    /**
     * The number of Bitcoin fraction digits.
     *
     * @var int
     */
    protected const BITCOINT_FRACTION_DIGITS = 2;

    /**
     * The currency code or the model attribute holding the currency code.
     *
     * @var string|null
     */
    protected $currency;

    /**
     * Instantiate the class.
     *
     * @param string|null $currency
     */
    public function __construct(string $currency = null)
    {
        $this->currency = $currency;
    }

    /**
     * Transform the attribute from the underlying model values.
     *
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @param  string  $key
     * @param  mixed  $value
     * @param  array  $attributes
     * @return mixed
     */
    public function get($model, string $key, $value, array $attributes)
    {
        if ($value === null) {
            return $value;
        }

        $parser = new DecimalMoneyParser($this->getAllCurrencies());
        $currency = $this->getCurrency($attributes);
        $money = $parser->parse($value, $currency);

        return new Money($money->getAmount(), $currency);
    }

    /**
     * Retrieve all available currencies
     *
     * @return \Money\Currencies\AggregateCurrencies
     */
    protected function getAllCurrencies(): AggregateCurrencies
    {
        static $currencies;

        if ($currencies) {
            return $currencies;
        }

        return $currencies = new AggregateCurrencies([
            new ISOCurrencies,
            new BitcoinCurrencies,
        ]);
    }

    /**
     * Retrieve the money
     *
     * @param array $attributes
     * @return \Money\Currency
     */
    protected function getCurrency(array $attributes): Currency
    {
        $defaultCode = Config::get('money.currency');

        if ($this->currency === null) {
            return new Currency($defaultCode);
        }

        $currency = new Currency($this->currency);

        if ($this->getAllCurrencies()->contains($currency)) {
            return $currency;
        }

        $code = $attributes[$this->currency] ?? $defaultCode;

        return new Currency($code);
    }

    /**
     * Transform the attribute to its underlying model values.
     *
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @param  string  $key
     * @param  mixed  $value
     * @param  array  $attributes
     * @return array
     */
    public function set($model, string $key, $value, array $attributes)
    {
        if ($value === null) {
            return [$key => $value];
        }

        $currency = $this->getCurrency($attributes);

        if (!$money = $this->toBaseMoney($value, $currency)) {
            throw new InvalidArgumentException(sprintf('Invalid data provided for %s::$%s', get_class($model), $key));
        }

        $amount = (new DecimalMoneyFormatter($this->getAllCurrencies()))->format($money);

        if (array_key_exists($this->currency, $attributes)) {
            return [$key => $amount, $this->currency => $money->getCurrency()->getCode()];
        }

        return [$key => $amount];
    }

    /**
     * Convert the given value into an instance of Money
     *
     * @param mixed $value
     * @param \Money\Currency $currency
     * @return \Money\Money|null
     */
    protected function toBaseMoney($value, Currency $currency): ?BaseMoney
    {
        if ($value instanceof BaseMoney) {
            return $value;
        }

        if ($this->shouldBeParsed($value)) {
            return $this->parse($value, $currency);
        }

        if (is_string($value)) {
            return new BaseMoney($value, $currency);
        }

        if ($this->isRaw($value)) {
            $value = Money::parseByDecimal((string) $value, $currency);
        }

        return $value instanceof Money ? $value->getMoney() : null;
    }

    /**
     * Determine whether the given value should be parsed
     *
     * @param mixed $value
     * @return bool
     */
    protected function shouldBeParsed($value): bool
    {
        return is_string($value) && filter_var($value, FILTER_VALIDATE_FLOAT) === false;
    }

    /**
     * Retrieve an instance of Money by parsing the given value
     *
     * @param string $value
     * @param \Money\Currency $currency
     * @return \Money\Money
     * @throws \Money\Exception\ParserException
     */
    protected function parse(string $value, Currency $currency): BaseMoney
    {
        $locale = Config::get('money.locale');
        $currencies = $this->getAllCurrencies();
        $decimalFormatter = new NumberFormatter($locale, NumberFormatter::DECIMAL);
        $decimalParser = new IntlLocalizedDecimalParser($decimalFormatter, $currencies);
        $currencyFormatter = new NumberFormatter($locale, NumberFormatter::CURRENCY);
        $currencyParsers = new AggregateMoneyParser([
            new IntlMoneyParser($currencyFormatter, $currencies),
            new BitcoinMoneyParser(static::BITCOINT_FRACTION_DIGITS),
        ]);

        try {
            return $currencyParsers->parse($value);
        } catch (ParserException $e) {
            try {
                return $decimalParser->parse($value, $currency);
            } catch (ParserException $e) {
                throw new ParserException("Unable to parse {$value}");
            }
        }
    }

    /**
     * Determine whether the given value is raw
     *
     * @param mixed $value
     * @return bool
     */
    protected function isRaw($value): bool
    {
        return is_int($value) || is_float($value);
    }
}
