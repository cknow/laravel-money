<?php

namespace Cknow\Money\Serializer;

use Cknow\Money\Money;
use Cknow\Money\MoneySerializer;

class ArrayMoneySerializer implements MoneySerializer
{
    /**
     * Formats a Money object as string.
     *
     * @param  \Cknow\Money\Money  $money
     * @return array
     */
    public function serialize(Money $money)
    {
        return array_merge(
            $money->getAttributes(),
            $money->getMoney()->jsonSerialize(),
            ['formatted' => $money->format()]
        );
    }
}
