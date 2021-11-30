<?php

namespace Cknow\Money\Tests;

use Cknow\Money\Money;
use InvalidArgumentException;
use Money\Currency;
use Money\Exception\ParserException;
use Money\Parser\BitcoinMoneyParser;
use Money\Parser\DecimalMoneyParser;
use Money\Parser\IntlMoneyParser;
use NumberFormatter;
use stdClass;

class MoneyParserTraitTest extends TestCase
{
    public function testParse()
    {
        static::assertEquals(Money::parse('100.00', 'USD'), Money::USD(10000));
        static::assertEquals(Money::parse('$1.00'), Money::USD(100));
        static::assertEquals(Money::parse('$1.00', 'USD'), Money::USD(100));
        static::assertEquals(Money::parse(1, 'USD'), Money::USD(1));
        static::assertEquals(Money::parse(1.10, 'USD'), Money::USD(110));
        static::assertEquals(Money::parse('100', 'USD'), Money::USD(100));
        static::assertEquals(Money::parse('1', 'USD'), Money::USD(1));
        static::assertEquals(Money::parse(Money::USD(100)), Money::USD(100));
        static::assertEquals(Money::parse(new \Money\Money(100, new Currency('USD'))), Money::USD(100));
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
        static::assertEquals(Money::parseByParser($parser, '1.00', new Currency('EUR')), Money::EUR(100));
    }

    public function testParseInvalidMoneyValue()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid value {}');

        Money::parse(new stdClass());
    }

    public function testParseInvalidMoney()
    {
        $this->expectException(ParserException::class);
        $this->expectExceptionMessage('Unable to parse abc');

        Money::parse('abc');
    }
}
