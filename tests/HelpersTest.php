<?php

use Clicknow\Money\Currency;
use Clicknow\Money\Money;

class HelpersTest extends PHPUnit_Framework_TestCase
{
    public function testMoney()
    {
        $this->assertEquals(new Currency('BRL'), currency('BRL'));
        $this->assertEquals(new Currency('USD'), currency('USD'));
    }

    public function testCurrency()
    {
        $this->assertEquals(new Money(25, new Currency('BRL')), money(25, 'BRL'));
        $this->assertEquals(new Money(25, new Currency('USD')), money(25, 'USD'));
    }
}
