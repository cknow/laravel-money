<?php

namespace Cknow\Money\Serializer;

use Cknow\Money\Money;
use Cknow\Money\MoneySerializer;

class ArrayMoneySerializer implements MoneySerializer
{
    /**
     * Formats a Money object as string.
     *
     * @param  \Money\Money  $moeny
     * @return mixed
     */
    public function serialize(Money $money): mixed
    {
        return array_merge(
            $money->getAttributes(),
            $money->getMoney()->jsonSerialize(),
            ['formatted' => $money->render()]
        );
    }
}
