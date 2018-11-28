<?php

namespace Cknow\Money;

use Money\Currencies\ISOCurrencies;
use Money\Currency;
use Money\Formatter\DecimalMoneyFormatter;
use NumberFormatter as N;

/**
 * @covers \Cknow\Money\MoneyFormatterTrait
 */
class MoneyFormatterTraitTest extends \PHPUnit\Framework\TestCase
{
    protected function setUp()
    {
        Money::setCurrencies(new ISOCurrencies());
        Money::setLocale('en_US');
    }

    public function testFormat()
    {
        static::assertEquals('$1.00', Money::USD(100)->format());
        static::assertEquals('€1.00', Money::EUR(100)->format());
        static::assertEquals('€1.00', Money::EUR(100)->format('en_US'));
        static::assertEquals('$1.00', Money::USD(100)->format('en_US', Money::getCurrencies(), N::CURRENCY));
        static::assertEquals('1,99', Money::EUR(199)->format('fr_FR', Money::getCurrencies(), N::DECIMAL));
        static::assertEquals('1', Money::USD(100)->format('en_US', Money::getCurrencies(), N::DECIMAL));
    }

    public function testFormatByDecimal()
    {
        static::assertEquals('1.00', Money::USD(100)->formatByDecimal(Money::getCurrencies()));
        static::assertEquals('1.00', Money::USD(100)->formatByDecimal());
    }

    public function testFormatByFormatter()
    {
        $formatter = new DecimalMoneyFormatter(Money::getCurrencies());

        static::assertEquals('1.00', Money::USD(100)->formatByFormatter($formatter));
    }
}
