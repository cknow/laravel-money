<?php

namespace Cknow\Money\Casts;

use Cknow\Money\Money;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use InvalidArgumentException;

abstract class MoneyCast implements CastsAttributes
{
    /**
     * The currency code or the model attribute holding the currency code.
     *
     * @var string|null
     */
    protected $currency;

    /**
     * Force decimals.
     *
     * @var bool
     */
    protected $forceDecimals = false;

    /**
     * Instantiate the class.
     *
     * @param  string|null  $currency
     * @param  mixed  $forceDecimals
     */
    public function __construct(string $currency = null, $forceDecimals = null)
    {
        $this->currency = $currency;
        $this->forceDecimals = is_string($forceDecimals)
            ? filter_var($forceDecimals, FILTER_VALIDATE_BOOLEAN)
            : (bool) $forceDecimals;
    }

    /**
     * Get formatter.
     *
     * @param  \Cknow\Money\Money  $money
     * @return string|float|int
     */
    abstract protected function getFormatter(Money $money);

    /**
     * Prepare value to parser.
     *
     * @param  mixed  $value
     * @return mixed
     */
    protected function prepareValue($value)
    {
        if (! $this->forceDecimals) {
            return $value;
        }

        if (is_int($value) || (filter_var($value, FILTER_VALIDATE_INT) !== false && ! is_float($value))) {
            return sprintf('%.14F', $value);
        }

        return $value;
    }

    /**
     * Transform the attribute from the underlying model values.
     *
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @param  string  $key
     * @param  mixed  $value
     * @param  array  $attributes
     * @return \Cknow\Money\Money|null
     */
    public function get($model, string $key, $value, array $attributes)
    {
        if ($value === null) {
            return $value;
        }

        $value = $this->prepareValue($value);
        $currency = $this->getCurrency($attributes);

        return Money::parse($value, $currency);
    }

    /**
     * Transform the attribute to its underlying model values.
     *
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @param  string  $key
     * @param  mixed  $value
     * @param  array  $attributes
     * @return array
     *
     * @throws \InvalidArgumentException
     */
    public function set($model, string $key, $value, array $attributes)
    {
        if ($value === null) {
            return [$key => $value];
        }

        try {
            $value = $this->prepareValue($value);
            $currency = $this->getCurrency($attributes);
            $money = Money::parse($value, $currency);
        } catch (InvalidArgumentException $e) {
            throw new InvalidArgumentException(
                sprintf('Invalid data provided for %s::$%s', get_class($model), $key)
            );
        }

        $amount = $this->getFormatter($money);

        if (array_key_exists($this->currency, $attributes)) {
            return [$key => $amount, $this->currency => $money->getCurrency()->getCode()];
        }

        return [$key => $amount];
    }

    /**
     * Get currency.
     *
     * @param  array  $attributes
     * @return \Money\Currency
     */
    protected function getCurrency(array $attributes)
    {
        $defaultCode = Money::getDefaultCurrency();

        if ($this->currency === null) {
            return Money::parseCurrency($defaultCode);
        }

        $currency = Money::parseCurrency($this->currency);
        $currencies = Money::getCurrencies();

        if ($currencies->contains($currency)) {
            return $currency;
        }

        $code = $attributes[$this->currency] ?? $defaultCode;

        return Money::parseCurrency($code);
    }
}
