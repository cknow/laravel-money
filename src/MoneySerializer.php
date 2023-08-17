<?php

namespace Cknow\Money;

/**
 * Serializes Money objects.
 */
interface MoneySerializer
{
    public function serialize(Money $money);
}
