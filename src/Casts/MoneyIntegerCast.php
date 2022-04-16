<?php

namespace Cknow\Money\Casts;

use Cknow\Money\Money;

class MoneyIntegerCast extends MoneyCast
{
    /**
     * Get formatter.
     *
     * @param  \Cknow\Money\Money  $money
     * @return int
     */
    protected function getFormatter(Money $money)
    {
        return (int) $money->getAmount();
    }
}
