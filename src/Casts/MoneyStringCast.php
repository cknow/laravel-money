<?php

namespace Cknow\Money\Casts;

use Cknow\Money\Money;

class MoneyStringCast extends MoneyCast
{
    /**
     * Get formatter.
     *
     * @return string
     */
    protected function getFormatter(Money $money)
    {
        return (string) $money->formatByIntl();
    }
}
