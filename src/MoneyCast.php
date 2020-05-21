<?php

namespace Cknow\Money;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use InvalidArgumentException;
use Money\Currencies\AggregateCurrencies;
use Money\Currencies\BitcoinCurrencies;
use Money\Currencies\ISOCurrencies;
use Money\Currency;
use Money\Exception\ParserException;
use Money\Money as BaseMoney;
use Money\Parser\BitcoinMoneyParser;
use Money\Parser\IntlMoneyParser;
use NumberFormatter;

/**
 * Cast model attributes into Money.
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
     * @param \Illuminate\Database\Eloquent\Model $model
     * @param string                              $key
     * @param mixed                               $value
     * @param array                               $attributes
     *
     * @return mixed
     */
    public function get($model, string $key, $value, array $attributes)
    {
        if (null === $value) {
            return $value;
        }

        return Money::parseByDecimal(
            $value,
            $this->getCurrency($attributes),
            $this->getAllCurrencies()
        );
    }

    /**
     * Transform the attribute to its underlying model values.
     *
     * @param \Illuminate\Database\Eloquent\Model $model
     * @param string                              $key
     * @param mixed                               $value
     * @param array                               $attributes
     *
     * @return array
     */
    public function set($model, string $key, $value, array $attributes)
    {
        if (null === $value) {
            return [$key => $value];
        }

        $currency = $this->getCurrency($attributes);
        $money = $this->toMoney($value, $currency);

        if (!$money) {
            throw new InvalidArgumentException(sprintf('Invalid data provided for %s::$%s', get_class($model), $key));
        }

        $amount = $money->formatByDecimal($this->getAllCurrencies());

        if (array_key_exists($this->currency, $attributes)) {
            return [$key => $amount, $this->currency => $money->getCurrency()->getCode()];
        }

        return [$key => $amount];
    }

    /**
     * Retrieve all available currencies.
     *
     * @return \Money\Currencies\AggregateCurrencies
     */
    protected function getAllCurrencies()
    {
        static $currencies;

        if ($currencies) {
            return $currencies;
        }

        return $currencies = new AggregateCurrencies([
            new ISOCurrencies(),
            new BitcoinCurrencies(),
        ]);
    }

    /**
     * Retrieve the money.
     *
     * @param array $attributes
     *
     * @return \Money\Currency
     */
    protected function getCurrency(array $attributes)
    {
        $defaultCode = Money::getDefaultCurrency();

        if (null === $this->currency) {
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
     * Convert the given value into an instance of Money.
     *
     * @param mixed           $value
     * @param \Money\Currency $currency
     *
     * @return \Cknow\Money\Money|null
     */
    protected function toMoney($value, Currency $currency)
    {
        if ($value instanceof Money) {
            return $value;
        }

        if ($value instanceof BaseMoney) {
            return Money::fromMoney($value);
        }

        if ($this->shouldBeParsed($value)) {
            return $this->parse($value, $currency);
        }

        if (is_string($value)) {
            return new Money($value, $currency);
        }

        return $this->isRaw($value)
            ? Money::parseByDecimal((string) $value, $currency)
            : null;
    }

    /**
     * Determine whether the given value should be parsed.
     *
     * @param mixed $value
     *
     * @return bool
     */
    protected function shouldBeParsed($value)
    {
        return is_string($value) && false === filter_var($value, FILTER_VALIDATE_FLOAT);
    }

    /**
     * Retrieve an instance of Money by parsing the given value.
     *
     * @param string          $value
     * @param \Money\Currency $currency
     *
     * @throws \Money\Exception\ParserException
     *
     * @return \Cknow\Money\Money
     */
    protected function parse(string $value, Currency $currency)
    {
        $locale = Money::getLocale();
        $currencies = $this->getAllCurrencies();
        $parsers = [
            new IntlMoneyParser(new NumberFormatter($locale, NumberFormatter::CURRENCY), $currencies),
            new BitcoinMoneyParser(static::BITCOINT_FRACTION_DIGITS),
        ];

        try {
            return Money::parseByAggregate($value, null, $parsers);
        } catch (ParserException $e) {
            try {
                return Money::parseByIntlLocalizedDecimal($value, $currency, $locale, $currencies);
            } catch (ParserException $e) {
                throw new ParserException("Unable to parse {$value}");
            }
        }
    }

    /**
     * Determine whether the given value is raw.
     *
     * @param mixed $value
     *
     * @return bool
     */
    protected function isRaw($value)
    {
        return is_int($value) || is_float($value);
    }
}
