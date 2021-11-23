<?php

namespace Cknow\Money;

class MoneyStringCast extends MoneyCast
{
    protected function getFormatter(Money $money)
    {
        return $money->format();
    }
}
