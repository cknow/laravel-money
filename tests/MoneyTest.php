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
    public function setUp()
    {
        Money::setCurrencies(new ISOCurrencies());
        Money::setLocale('pt_BR');
    }

    public function testFactoryMethods()
    {
        $this->assertEquals(Money::BRL(10), new Money(10, new Currency('BRL')));
    }

    public function testParse()
    {
        $this->assertEquals(Money::parse('R$1,00'), Money::BRL(100));
        $this->assertEquals(Money::parse('R$1,00', 'BRL'), Money::BRL(100));
        $this->assertEquals(Money::parse('$1.00', 'USD', 'en_US'), Money::USD(100));
        $this->assertEquals(Money::parse('$1.00', 'USD', 'en_US', Money::getCurrencies()), Money::USD(100));
    }

    public function testParseByDecimal()
    {
        $this->assertEquals(Money::parseByDecimal('1.00', 'BRL'), Money::BRL(100));
        $this->assertEquals(Money::parseByDecimal('1.00', 'USD', Money::getCurrencies()), Money::USD(100));
    }

    public function testParseByParser()
    {
        $parser = new DecimalMoneyParser(Money::getCurrencies());

        $this->assertEquals(Money::parseByParser($parser, '1.00', 'BRL'), Money::BRL(100));
        $this->assertEquals(Money::parseByParser($parser, '1.00', 'USD'), Money::USD(100));
    }

    public function testConvert()
    {
        $this->assertEquals(Money::BRL(25), Money::convert(new \Money\Money(25, new Currency('BRL'))));
    }

    public function testAdd()
    {
        $this->assertEquals(Money::BRL(25), Money::BRL(10)->add(Money::BRL(15)));
        $this->assertEquals(Money::USD(25), Money::USD(10)->add(Money::USD(15)));
    }

    public function testSubtract()
    {
        $this->assertEquals(Money::BRL(15), Money::BRL(20)->subtract(Money::BRL(5)));
        $this->assertEquals(Money::USD(20), Money::USD(25)->subtract(Money::USD(5)));
    }

    public function testMod()
    {
        $this->assertEquals(Money::BRL(230), Money::BRL(830)->mod(Money::BRL(300)));
        $this->assertEquals(Money::USD(115), Money::USD(415)->mod(Money::USD(150)));
    }

    public function testRatioOf()
    {
        $this->assertEquals('15', Money::BRL(30)->ratioOf(Money::BRL(2)));
        $this->assertEquals('20', Money::USD(60)->ratioOf(Money::USD(3)));
    }

    public function testCallUndefinedMethod()
    {
        $this->assertEquals(Money::BRL(15), Money::BRL(15)->undefined());
    }

    public function testCallMethodInRealObject()
    {
        $this->assertTrue(Money::BRL(25)->isPositive());
        $this->assertTrue(Money::BRL(-25)->isNegative());
    }

    public function testCallMethodWhatNeedConvert()
    {
        $this->assertEquals(Money::BRL(10), Money::BRL(5)->multiply(2));
        $this->assertEquals(Money::BRL(10), Money::BRL(20)->divide(2));
        $this->assertEquals([Money::BRL(5), Money::BRL(5)], Money::BRL(10)->allocateTo(2));
    }

    public function testFormat()
    {
        $this->assertEquals('R$1,00', Money::BRL(100)->format());
        $this->assertEquals('US$1,00', Money::USD(100)->format());
        $this->assertEquals('R$1.00', Money::BRL(100)->format('en_US'));
        $this->assertEquals('$1.00', Money::USD(100)->format('en_US', Money::getCurrencies(), N::CURRENCY));
        $this->assertEquals('1,99', Money::BRL(199)->format('pt_BR', Money::getCurrencies(), N::DECIMAL));
        $this->assertEquals('1.00', Money::USD(100)->format('en_US', Money::getCurrencies(), N::DECIMAL));
    }

    public function testFormatByDecimal()
    {
        $this->assertEquals('1.00', Money::BRL(100)->formatByDecimal(Money::getCurrencies()));
        $this->assertEquals('1.00', Money::BRL(100)->formatByDecimal());
    }

    public function testFormatByFormatter()
    {
        $formatter = new DecimalMoneyFormatter(Money::getCurrencies());

        $this->assertEquals('1.00', Money::BRL(100)->formatByFormatter($formatter));
    }

    public function testGetters()
    {
        $money = new Money(100, new Currency('BRL'));

        $this->assertInstanceOf(\Money\Money::class, $money->getMoney());
        $this->assertJson($money->toJson());
        $this->assertEquals($money->toArray(), ['amount' => '100', 'currency' => 'BRL', 'formatted' => 'R$1,00']);
        $this->assertEquals($money->jsonSerialize(), ['amount' => '100', 'currency' => 'BRL', 'formatted' => 'R$1,00']);
        $this->assertEquals('R$1,00', $money->render());
        $this->assertEquals('R$1,00', $money);
    }

    public function testSerializeWithAttributes()
    {
        $money = new Money(100, new Currency('BRL'));
        $money->attributes(['foo' => 'bar']);

        $this->assertEquals(
            $money->jsonSerialize(),
            ['amount' => '100', 'currency' => 'BRL', 'formatted' => 'R$1,00', 'foo' => 'bar']
        );
    }
}
