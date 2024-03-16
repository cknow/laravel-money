<?php

namespace Cknow\Money\Casts;

use Cknow\Money\Money;

class MoneyDecimalCast extends MoneyCast
{
    /**
     * Get formatter.
     *
     * @return float
     */
    protected function getFormatter(Money $money)
    {
        return (float) $money->formatByDecimal();
    }
}
