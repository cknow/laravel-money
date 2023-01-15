<?php

namespace Cknow\Money\Serializer;

use Cknow\Money\Money;
use Cknow\Money\MoneySerializer;

class StringMoneySerializer implements MoneySerializer
{
    /**
     * Formats a Money object as string.
     *
     * @param  \Money\Money  $moeny
     * @return mixed
     */
    public function serialize(Money $money): mixed
    {
        return (string) $money->formatByIntl();
    }
}
