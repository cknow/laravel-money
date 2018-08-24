<?php

namespace Cknow\Money;

use Money\Currencies\ISOCurrencies;
use Money\Currency;
use Money\Formatter\DecimalMoneyFormatter;
use Money\Parser\DecimalMoneyParser;
use NumberFormatter as N;

/**
 * @covers \Cknow\Money\Money
 */
class MoneyTest extends \PHPUnit\Framework\TestCase
{
    protected function setUp()
    {
        Money::setCurrencies(new ISOCurrencies());
        Money::setLocale('pt_BR');
    }

    public function testFactoryMethods()
    {
        static::assertEquals(Money::BRL(10), new Money(10, new Currency('BRL')));
    }

    public function testParse()
    {
        static::assertEquals(Money::parse('R$1,00'), Money::BRL(100));
        static::assertEquals(Money::parse('R$1,00', 'BRL'), Money::BRL(100));
        static::assertEquals(Money::parse('$1.00', 'USD', 'en_US'), Money::USD(100));
        static::assertEquals(Money::parse('$1.00', 'USD', 'en_US', Money::getCurrencies()), Money::USD(100));
    }

    public function testParseByDecimal()
    {
        static::assertEquals(Money::parseByDecimal('1.00', 'BRL'), Money::BRL(100));
        static::assertEquals(Money::parseByDecimal('1.00', 'USD', Money::getCurrencies()), Money::USD(100));
    }

    public function testParseByParser()
    {
        $parser = new DecimalMoneyParser(Money::getCurrencies());

        static::assertEquals(Money::parseByParser($parser, '1.00', 'BRL'), Money::BRL(100));
        static::assertEquals(Money::parseByParser($parser, '1.00', 'USD'), Money::USD(100));
    }

    public function testConvert()
    {
        static::assertEquals(Money::BRL(25), Money::convert(new \Money\Money(25, new Currency('BRL'))));
    }

    public function testAdd()
    {
        static::assertEquals(Money::BRL(25), Money::BRL(10)->add(Money::BRL(15)));
        static::assertEquals(Money::USD(25), Money::USD(10)->add(Money::USD(15)));
    }

    public function testSubtract()
    {
        static::assertEquals(Money::BRL(15), Money::BRL(20)->subtract(Money::BRL(5)));
        static::assertEquals(Money::USD(20), Money::USD(25)->subtract(Money::USD(5)));
    }

    public function testMod()
    {
        static::assertEquals(Money::BRL(230), Money::BRL(830)->mod(Money::BRL(300)));
        static::assertEquals(Money::USD(115), Money::USD(415)->mod(Money::USD(150)));
    }

    public function testRatioOf()
    {
        static::assertEquals('15', (float) Money::BRL(30)->ratioOf(Money::BRL(2)));
        static::assertEquals('20', (float) Money::USD(60)->ratioOf(Money::USD(3)));
    }

    public function testCallUndefinedMethod()
    {
        static::assertEquals(Money::BRL(15), Money::BRL(15)->undefined());
    }

    public function testCallMethodInRealObject()
    {
        static::assertTrue(Money::BRL(25)->isPositive());
        static::assertTrue(Money::BRL(-25)->isNegative());
    }

    public function testCallMethodWhatNeedConvert()
    {
        static::assertEquals(Money::BRL(10), Money::BRL(5)->multiply(2));
        static::assertEquals(Money::BRL(10), Money::BRL(20)->divide(2));
        static::assertEquals([Money::BRL(5), Money::BRL(5)], Money::BRL(10)->allocateTo(2));
    }

    public function testFormat()
    {
        static::assertEquals('R$1,00', Money::BRL(100)->format());
        static::assertEquals('US$1,00', Money::USD(100)->format());
        static::assertEquals('R$1.00', Money::BRL(100)->format('en_US'));
        static::assertEquals('$1.00', Money::USD(100)->format('en_US', Money::getCurrencies(), N::CURRENCY));
        static::assertEquals('1,99', Money::BRL(199)->format('pt_BR', Money::getCurrencies(), N::DECIMAL));
        static::assertEquals('1', Money::USD(100)->format('en_US', Money::getCurrencies(), N::DECIMAL));
    }

    public function testFormatByDecimal()
    {
        static::assertEquals('1.00', Money::BRL(100)->formatByDecimal(Money::getCurrencies()));
        static::assertEquals('1.00', Money::BRL(100)->formatByDecimal());
    }

    public function testFormatByFormatter()
    {
        $formatter = new DecimalMoneyFormatter(Money::getCurrencies());

        static::assertEquals('1.00', Money::BRL(100)->formatByFormatter($formatter));
    }

    public function testGetters()
    {
        $money = new Money(100, new Currency('BRL'));
        $actual = ['amount' => '100', 'currency' => 'BRL', 'formatted' => 'R$1,00'];

        static::assertInstanceOf(\Money\Money::class, $money->getMoney());
        static::assertJson($money->toJson());
        static::assertEquals($money->toArray(), $actual);
        static::assertEquals($money->jsonSerialize(), $actual);
        static::assertEquals('R$1,00', $money->render());
        static::assertEquals('R$1,00', $money);
    }

    public function testSerializeWithAttributes()
    {
        $money = new Money(100, new Currency('BRL'));
        $money->attributes(['foo' => 'bar']);

        static::assertEquals(
            $money->jsonSerialize(),
            ['amount' => '100', 'currency' => 'BRL', 'formatted' => 'R$1,00', 'foo' => 'bar']
        );
    }
}
