<?php

namespace Cknow\Money;

use Money\Currencies\ISOCurrencies;
use Money\Currency;

class HelpersTest extends \PHPUnit\Framework\TestCase
{
    protected function setUp()
    {
        Money::setCurrencies(new ISOCurrencies());
        Money::setLocale('en_US');
    }

    public function testCurrency()
    {
        static::assertEquals(money(25, 'BRL'), new Money(25, new Currency('BRL')));
        static::assertEquals(money(25, 'USD'), new Money(25, new Currency('USD')));
    }

    public function testMoney()
    {
        static::assertEquals(currency('BRL'), new Currency('BRL'));
        static::assertEquals(currency('USD'), new Currency('USD'));
    }

    public function testMoneyParse()
    {
        static::assertEquals(money_parse('$1.00'), Money::USD(100));
        static::assertEquals(money_parse('$1.00', 'BRL'), Money::BRL(100));
        static::assertEquals(money_parse('$1.00', 'USD', 'en_US'), Money::USD(100));
        static::assertEquals(money_parse('$1.00', 'USD', 'en_US', Money::getCurrencies()), Money::USD(100));
    }

    public function testMoneyParseByDecimal()
    {
        static::assertEquals(money_parse_by_decimal('5.00', 'BRL'), Money::BRL(500));
        static::assertEquals(money_parse_by_decimal('5.00', 'USD', null), Money::USD(500));
    }
}
