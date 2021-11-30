<?php

namespace Cknow\Money\Tests;

use Cknow\Money\Money;
use Money\Currency;

class HelpersTest extends TestCase
{
    public function testCurrency()
    {
        static::assertEquals(money(25), new Money(25, new Currency('USD')));
        static::assertEquals(money(25, 'USD'), new Money(25, new Currency('USD')));
        static::assertEquals(money(25, 'EUR'), new Money(25, new Currency('EUR')));
    }

    public function testMoney()
    {
        static::assertEquals(currency('USD'), new Currency('USD'));
        static::assertEquals(currency('EUR'), new Currency('EUR'));
    }

    public function testMoneyMin()
    {
        static::assertEquals(money_min(Money::USD(10), Money::USD(20), Money::USD(30)), Money::USD(10));
        static::assertEquals(money_min(Money::EUR(10), Money::EUR(20), Money::EUR(30)), Money::EUR(10));
    }

    public function testMoneyMax()
    {
        static::assertEquals(money_max(Money::USD(10), Money::USD(20), Money::USD(30)), Money::USD(30));
        static::assertEquals(money_max(Money::EUR(10), Money::EUR(20), Money::EUR(30)), Money::EUR(30));
    }

    public function testMoneyAvg()
    {
        static::assertEquals(money_avg(Money::USD(10), Money::USD(20), Money::USD(30)), Money::USD(20));
        static::assertEquals(money_avg(Money::EUR(10), Money::EUR(20), Money::EUR(30)), Money::EUR(20));
    }

    public function testMoneySum()
    {
        static::assertEquals(money_sum(Money::USD(10), Money::USD(20), Money::USD(30)), Money::USD(60));
        static::assertEquals(money_sum(Money::EUR(10), Money::EUR(20), Money::EUR(30)), Money::EUR(60));
    }

    public function testMoneyParse()
    {
        static::assertEquals(money_parse('$1.00'), Money::USD(100));
        static::assertEquals(money_parse('$1.00', 'USD'), Money::USD(100));
    }

    public function testMoneyParseByBitcoin()
    {
        static::assertEquals(money_parse_by_bitcoin("\xC9\x831000.00"), Money::XBT(100000));
        static::assertEquals(money_parse_by_bitcoin("\xC9\x831000.00", null, 4), Money::XBT(10000000));
    }

    public function testMoneyParseByDecimal()
    {
        static::assertEquals(money_parse_by_decimal('5.00', 'EUR'), Money::EUR(500));
        static::assertEquals(money_parse_by_decimal('5.00', 'USD', null), Money::USD(500));
    }

    public function testMoneyParseIntl()
    {
        static::assertEquals(money_parse_by_intl('$1.00'), Money::USD(100));
        static::assertEquals(money_parse_by_intl('$1.00', 'EUR'), Money::EUR(100));
        static::assertEquals(money_parse_by_intl('$1.00', 'USD', 'en_US'), Money::USD(100));
        static::assertEquals(money_parse_by_intl('$1.00', 'USD', 'en_US', Money::getCurrencies()), Money::USD(100));
    }

    public function testMoneyParseIntlLocalizedDecimal()
    {
        static::assertEquals(money_parse_by_intl_localized_decimal('1.00', 'USD'), Money::USD(100));
        static::assertEquals(money_parse_by_intl_localized_decimal('1.00', 'EUR'), Money::EUR(100));
        static::assertEquals(money_parse_by_intl_localized_decimal('1.00', 'USD', 'en_US'), Money::USD(100));
        static::assertEquals(
            money_parse_by_intl_localized_decimal('1.00', 'USD', 'en_US', Money::getCurrencies()),
            Money::USD(100)
        );
    }
}
