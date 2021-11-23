<?php

namespace Cknow\Money;

class MoneyDecimalCast extends MoneyCast
{
    protected function getFormatter(Money $money)
    {
        return $money->formatByDecimal();
    }
}
