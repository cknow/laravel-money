<?php

namespace Cknow\Money;

use Money\Currencies\ISOCurrencies;
use Money\Currency;

class HelpersTest extends \PHPUnit\Framework\TestCase
{
    public function setUp()
    {
        Money::setCurrencies(new ISOCurrencies());
        Money::setLocale('pt_BR');
    }

    public function testCurrency()
    {
        $this->assertEquals(money(25, 'BRL'), new Money(25, new Currency('BRL')));
        $this->assertEquals(money(25, 'USD'), new Money(25, new Currency('USD')));
    }

    public function testMoney()
    {
        $this->assertEquals(currency('BRL'), new Currency('BRL'));
        $this->assertEquals(currency('USD'), new Currency('USD'));
    }

    public function testMoneyParse()
    {
        $this->assertEquals(money_parse('R$1,00'), Money::BRL(100));
        $this->assertEquals(money_parse('R$1,00', 'BRL'), Money::BRL(100));
        $this->assertEquals(money_parse('$1.00', 'USD', 'en_US'), Money::USD(100));
        $this->assertEquals(money_parse('$1.00', 'USD', 'en_US', Money::getCurrencies()), Money::USD(100));
    }
}
