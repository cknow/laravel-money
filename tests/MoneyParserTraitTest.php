<?php

namespace Cknow\Money;

use Money\Currencies\ISOCurrencies;
use Money\Parser\DecimalMoneyParser;

/**
 * @covers \Cknow\Money\MoneyParserTrait
 */
class MoneyParserTraitTest extends \PHPUnit\Framework\TestCase
{
    protected function setUp()
    {
        Money::setCurrencies(new ISOCurrencies());
        Money::setLocale('en_US');
    }

    public function testParse()
    {
        static::assertEquals(Money::parse('$1.00'), Money::USD(100));
        static::assertEquals(Money::parse('$1.00', 'EUR'), Money::EUR(100));
        static::assertEquals(Money::parse('$1.00', 'USD', 'en_US'), Money::USD(100));
        static::assertEquals(Money::parse('$1.00', 'USD', 'en_US', Money::getCurrencies()), Money::USD(100));
    }

    public function testParseByDecimal()
    {
        static::assertEquals(Money::parseByDecimal('1.00', 'EUR'), Money::EUR(100));
        static::assertEquals(Money::parseByDecimal('1.00', 'USD', Money::getCurrencies()), Money::USD(100));
    }

    public function testParseByParser()
    {
        $parser = new DecimalMoneyParser(Money::getCurrencies());

        static::assertEquals(Money::parseByParser($parser, '1.00', 'USD'), Money::USD(100));
        static::assertEquals(Money::parseByParser($parser, '1.00', 'EUR'), Money::EUR(100));
    }
}
