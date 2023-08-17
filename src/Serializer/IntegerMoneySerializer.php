<?php

namespace Cknow\Money\Serializer;

use Cknow\Money\Money;
use Cknow\Money\MoneySerializer;

class IntegerMoneySerializer implements MoneySerializer
{
    /**
     * Formats a Money object as string.
     *
     * @return int
     */
    public function serialize(Money $money)
    {
        return (int) $money->getAmount();
    }
}
