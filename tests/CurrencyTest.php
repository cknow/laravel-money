<?php

use Clicknow\Money\Currency;

class CurrencyTest extends PHPUnit_Framework_TestCase
{
    public function testFactoryMethods()
    {
        $this->assertEquals(Currency::BRL(), new Currency('BRL'));
        $this->assertEquals(Currency::USD(), new Currency('USD'));
    }

    /**
     * @expectedException \OutOfBoundsException
     */
    public function testCantInstantiateUnknownCurrency()
    {
        new Currency('unknown');
    }

    public function testComparison()
    {
        $c1 = new Currency('BRL');
        $c2 = new Currency('USD');

        $this->assertTrue($c1->equals(new Currency('BRL')));
        $this->assertTrue($c2->equals(new Currency('USD')));
        $this->assertFalse($c1->equals($c2));
        $this->assertFalse($c2->equals($c1));
    }

    public function testGetters()
    {
        $c = new Currency('BRL');

        $this->assertEquals('BRL', $c->getCurrency());
        $this->assertEquals('Brazilian Real', $c->getName());
        $this->assertEquals(986, $c->getCode());
        $this->assertEquals(2, $c->getPrecision());
        $this->assertEquals(100, $c->getSubunit());
        $this->assertEquals('R$', $c->getSymbol());
        $this->assertEquals(true, $c->isSymbolFirst());
        $this->assertEquals(',', $c->getDecimalMark());
        $this->assertEquals('.', $c->getThousandsSeparator());
    }

    public function testToString()
    {
        $this->assertEquals('BRL (Brazilian Real)', (string) new Currency('BRL'));
        $this->assertEquals('USD (US Dollar)', (string) new Currency('USD'));
    }
}
