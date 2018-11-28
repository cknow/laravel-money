<?php

namespace Cknow\Money;

use Money\Currencies\ISOCurrencies;
use Money\Currency;

/**
 * @covers \Cknow\Money\Money
 */
class MoneyTest extends \PHPUnit\Framework\TestCase
{
    protected function setUp()
    {
        Money::setCurrencies(new ISOCurrencies());
        Money::setLocale('en_US');
    }

    public function testConvert()
    {
        static::assertEquals(Money::USD(25), Money::convert(new \Money\Money(25, new Currency('USD'))));
    }

    public function testAdd()
    {
        static::assertEquals(Money::USD(25), Money::USD(10)->add(Money::USD(15)));
        static::assertEquals(Money::EUR(25), Money::EUR(10)->add(Money::EUR(15)));
    }

    public function testSubtract()
    {
        static::assertEquals(Money::USD(20), Money::USD(25)->subtract(Money::USD(5)));
        static::assertEquals(Money::EUR(15), Money::EUR(20)->subtract(Money::EUR(5)));
    }

    public function testMod()
    {
        static::assertEquals(Money::USD(115), Money::USD(415)->mod(Money::USD(150)));
        static::assertEquals(Money::EUR(230), Money::EUR(830)->mod(Money::EUR(300)));
    }

    public function testRatioOf()
    {
        static::assertEquals('20', (float) Money::USD(60)->ratioOf(Money::USD(3)));
        static::assertEquals('15', (float) Money::EUR(30)->ratioOf(Money::EUR(2)));
    }

    public function testCallUndefinedMethod()
    {
        static::assertEquals(Money::USD(15), Money::USD(15)->undefined());
    }

    public function testCallMethodInRealObject()
    {
        static::assertTrue(Money::USD(25)->isPositive());
        static::assertTrue(Money::USD(-25)->isNegative());
    }

    public function testCallMethodWhatNeedConvert()
    {
        static::assertEquals(Money::USD(10), Money::USD(5)->multiply(2));
        static::assertEquals(Money::USD(10), Money::USD(20)->divide(2));
        static::assertEquals([Money::USD(5), Money::USD(5)], Money::USD(10)->allocateTo(2));
    }

    public function testGetters()
    {
        $money = new Money(100, new Currency('USD'));
        $actual = ['amount' => '100', 'currency' => 'USD', 'formatted' => '$1.00'];

        static::assertInstanceOf(\Money\Money::class, $money->getMoney());
        static::assertJson($money->toJson());
        static::assertEquals($money->toArray(), $actual);
        static::assertEquals($money->jsonSerialize(), $actual);
        static::assertEquals('$1.00', $money->render());
        static::assertEquals('$1.00', $money);
    }

    public function testSerializeWithAttributes()
    {
        $money = new Money(100, new Currency('USD'));
        $money->attributes(['foo' => 'bar']);

        static::assertEquals(
            $money->jsonSerialize(),
            ['amount' => '100', 'currency' => 'USD', 'formatted' => '$1.00', 'foo' => 'bar']
        );
    }
}
