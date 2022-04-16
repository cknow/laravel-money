<?php

namespace Cknow\Money\Casts;

use Cknow\Money\Money;

class MoneyDecimalCast extends MoneyCast
{
    /**
     * Get formatter.
     *
     * @param  \Cknow\Money\Money  $money
     * @return float
     */
    protected function getFormatter(Money $money)
    {
        return (float) $money->formatByDecimal();
    }
}
