<?php

namespace Cknow\Money;

use Money\Currencies\ISOCurrencies;
use Money\Parser\BitcoinMoneyParser;
use Money\Parser\DecimalMoneyParser;
use Money\Parser\IntlMoneyParser;
use NumberFormatter;

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

    public function testParseByAggregate()
    {
        $parsers = [
            new BitcoinMoneyParser(2),
            new DecimalMoneyParser(Money::getCurrencies()),
            new IntlMoneyParser(
                new NumberFormatter(Money::getLocale(), NumberFormatter::CURRENCY),
                Money::getCurrencies()
            ),
        ];

        static::assertEquals(Money::parseByAggregate("\xC9\x831000.00", 'EUR', $parsers), Money::XBT(100000));
        static::assertEquals(Money::parseByAggregate('1.00', 'EUR', $parsers), Money::EUR(100));
        static::assertEquals(Money::parseByAggregate('$1.00', 'EUR', $parsers), Money::EUR(100));
    }

    public function testParseByBitcoin()
    {
        static::assertEquals(Money::parseByBitcoin("\xC9\x831000.00"), Money::XBT(100000));
        static::assertEquals(Money::parseByBitcoin("-\xC9\x831"), Money::XBT(-100));
        static::assertEquals(Money::parseByBitcoin("\xC9\x831000.00", null, 4), Money::XBT(10000000));
    }

    public function testParseByDecimal()
    {
        static::assertEquals(Money::parseByDecimal('1.00', 'EUR'), Money::EUR(100));
        static::assertEquals(Money::parseByDecimal('1.00', 'USD', Money::getCurrencies()), Money::USD(100));
    }

    public function testParseIntl()
    {
        static::assertEquals(Money::parseByIntl('$1.00'), Money::USD(100));
        static::assertEquals(Money::parseByIntl('$1.00', 'EUR'), Money::EUR(100));
        static::assertEquals(Money::parseByIntl('$1.00', 'USD', 'en_US'), Money::USD(100));
        static::assertEquals(Money::parseByIntl('$1.00', 'USD', 'en_US', Money::getCurrencies()), Money::USD(100));
    }

    public function testParseIntlLocalizedDecimal()
    {
        static::assertEquals(Money::parseByIntlLocalizedDecimal('1.00', 'USD'), Money::USD(100));
        static::assertEquals(Money::parseByIntlLocalizedDecimal('1.00', 'EUR'), Money::EUR(100));
        static::assertEquals(Money::parseByIntlLocalizedDecimal('1.00', 'USD', 'en_US'), Money::USD(100));
        static::assertEquals(
            Money::parseByIntlLocalizedDecimal('1.00', 'USD', 'en_US', Money::getCurrencies()),
            Money::USD(100)
        );
    }

    public function testParseByParser()
    {
        $parser = new DecimalMoneyParser(Money::getCurrencies());

        static::assertEquals(Money::parseByParser($parser, '1.00', 'USD'), Money::USD(100));
        static::assertEquals(Money::parseByParser($parser, '1.00', 'EUR'), Money::EUR(100));
    }
}
