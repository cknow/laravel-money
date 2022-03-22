<?php

namespace Cknow\Money\Casts;

use Cknow\Money\Money;

class MoneyDecimalCast extends MoneyCast
{
    /**
     * Get formatter.
     *
     * @param  \Cknow\Money\Money  $money
     * @return mixed
     */
    protected function getFormatter(Money $money)
    {
        return $money->formatByDecimal();
    }

    public function get($model, string $key, $value, array $attributes)
    {
        if (filter_var($value, FILTER_VALIDATE_INT) !== false) {
            $value = number_format($value, '2');
        }

        return parent::get($model, $key, $value, $attributes);
    }

    public function set($model, string $key, $value, array $attributes)
    {
        if (filter_var($value, FILTER_VALIDATE_INT) !== false) {
            $value = number_format($value, '2');
        }

        return parent::set($model, $key, $value, $attributes);
    }
}
