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
     * Instantiate the class.
     *
     * @param  string|null  $currency
     */
    public function __construct(string $currency = null)
    {
        $this->currency = $currency;
    }

    /**
     * Get formatter.
     *
     * @param  \Cknow\Money\Money  $money
     * @return mixed
     */
    abstract protected function getFormatter(Money $money);

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

        return Money::parse($value, $this->getCurrency($attributes));
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
