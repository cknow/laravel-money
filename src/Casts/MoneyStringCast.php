<?php

namespace Cknow\Money\Casts;

use Cknow\Money\Money;

class MoneyStringCast extends MoneyCast
{
    /**
     * Get formatter.
     *
     * @param  \Cknow\Money\Money  $money
     * @return string
     */
    protected function getFormatter(Money $money)
    {
        return (string) $money->formatByIntl();
    }
}
