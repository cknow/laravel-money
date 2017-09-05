<?php

namespace Cknow\Money;

use Money\Currencies\ISOCurrencies;
use Money\Currency;

/**
 * @covers \Cknow\Money\Money
 */
class MoneyTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        Money::setCurrencies(new ISOCurrencies());
        Money::setLocale('pt_BR');
    }

    public function testFactoryMethods()
    {
        $this->assertEquals(Money::BRL(10), new Money(10, new Currency('BRL')));
    }

    public function testCallMethodsInRealObject()
    {
        $this->assertEquals(Money::BRL(25)->getMoney(), Money::BRL(10)->add(Money::BRL(15)->getMoney()));
        $this->assertEquals(Money::USD(25)->getMoney(), Money::USD(10)->add(Money::USD(15)->getMoney()));
    }

    public function testGetters()
    {
        $money = new Money(100, new Currency('BRL'));

        $this->assertInstanceOf(\Money\Money::class, $money->getMoney());
        $this->assertArrayHasKey('amount', $money->toArray());
        $this->assertJson($money->toJson());
        $this->assertArrayHasKey('amount', $money->jsonSerialize());
        $this->assertEquals('R$1,00', $money->render());
        $this->assertEquals('R$1,00', $money);
    }

    public function testFormat()
    {
        $this->assertEquals('R$1,00', Money::BRL(100)->format());
        $this->assertEquals('US$1,00', Money::USD(100)->format());
        $this->assertEquals('R$1.00', Money::BRL(100)->format('en_US'));
        $this->assertEquals('$1.00', Money::USD(100)->format('en_US', Money::getCurrencies()));
    }

    public function testFormatSimple()
    {
        $this->assertEquals('1.00', Money::BRL(100)->formatSimple(Money::getCurrencies()));
        $this->assertEquals('1.00', Money::BRL(100)->formatSimple());
    }
}
