<?php

namespace Cknow\Money\Serializer;

use Cknow\Money\Money;
use Cknow\Money\MoneySerializer;

class StringMoneySerializer implements MoneySerializer
{
    /**
     * Formats a Money object as string.
     *
     * @param  \Cknow\Money\Money  $money
     * @return string
     */
    public function serialize(Money $money)
    {
        return (string) $money->formatByIntl();
    }
}
