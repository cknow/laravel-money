<?php

namespace Cknow\Money;

use Money\Currencies\ISOCurrencies;
use Money\Currency;

/**
 * @covers \Cknow\Money\MoneyFactory
 */
class MoneyFactoryTest extends \PHPUnit\Framework\TestCase
{
    protected function setUp(): void
    {
        Money::setCurrencies(new ISOCurrencies());
        Money::setLocale('en_US');
    }

    public function testFactoryMethods()
    {
        $money = Money::USD(10);

        static::assertInstanceOf(Money::class, $money);
        static::assertEquals(new Money(10, new Currency('USD')), $money);
    }
}
