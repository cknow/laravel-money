<?php

namespace Cknow\Money\Serializer;

use Cknow\Money\Money;
use Cknow\Money\MoneySerializer;

class DecimalMoneySerializer implements MoneySerializer
{
    /**
     * Formats a Money object as string.
     *
     * @param  \Money\Money  $money
     * @return mixed
     */
    public function serialize(Money $money): mixed
    {
        return $money->formatByDecimal();
    }
}
