<?php

namespace Cknow\Money;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use InvalidArgumentException;
use Money\Currency;

class MoneyCast implements CastsAttributes
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
     * @return \Cknow\Money\Money|null
     */
    public function get($model, string $key, $value, array $attributes)
    {
        if (null === $value) {
            return $value;
        }

        return Money::parseByDecimal(
            $value,
            $this->getCurrency($attributes),
            Money::getCurrencies()
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
     * @throws \InvalidArgumentException
     *
     * @return array
     */
    public function set($model, string $key, $value, array $attributes)
    {
        if (null === $value) {
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

        $amount = $money->formatByDecimal(Money::getCurrencies());

        if (array_key_exists($this->currency, $attributes)) {
            return [$key => $amount, $this->currency => $money->getCurrency()->getCode()];
        }

        return [$key => $amount];
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
        $currencies = Money::getCurrencies();

        if ($currencies->contains($currency)) {
            return $currency;
        }

        $code = $attributes[$this->currency] ?? $defaultCode;

        return new Currency($code);
    }
}
