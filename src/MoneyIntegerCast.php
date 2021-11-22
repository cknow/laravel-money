<?php

namespace Cknow\Money;

class MoneyIntegerCast extends MoneyCast
{
    protected function getFormatter(Money $money)
    {
        return $money->getAmount();
    }
}
