<?php

use Clicknow\Money\Money;
use Clicknow\Money\Currency;

class MoneyTest extends PHPUnit_Framework_TestCase
{
    public function testFactoryMethods()
    {
        $this->assertEquals(Money::BRL(25), Money::BRL(10)->add(Money::BRL(15)));
        $this->assertEquals(Money::USD(25), Money::USD(10)->add(Money::USD(15)));
    }

    public function testConvertUnit()
    {
        $m1 = new Money(100, new Currency('BRL'), true);
        $m2 = new Money(100, new Currency('BRL'));

        $this->assertEquals(10000, $m1->getAmount());
        $this->assertNotEquals($m1, $m2);
    }

    /**
     * @expectedException \Clicknow\Money\Exceptions\MoneyException
     */
    public function testStringThrowsException()
    {
        new Money('foo', new Currency('BRL'));
    }

    public function testGetters()
    {
        $m = new Money(100, new Currency('BRL'));
        $this->assertEquals(100, $m->getAmount());
        $this->assertEquals(1, $m->getValue());
        $this->assertEquals(new Currency('BRL'), $m->getCurrency());
    }

    public function testSameCurrency()
    {
        $m = new Money(100, new Currency('BRL'));
        $this->assertTrue($m->isSameCurrency(new Money(100, new Currency('BRL'))));
        $this->assertFalse($m->isSameCurrency(new Money(100, new Currency('USD'))));
    }

    public function testComparison()
    {
        $m1 = new Money(50, new Currency('BRL'));
        $m2 = new Money(100, new Currency('BRL'));
        $m3 = new Money(200, new Currency('BRL'));

        $this->assertEquals(-1, $m2->compare($m3));
        $this->assertEquals(1, $m2->compare($m1));
        $this->assertEquals(0, $m2->compare($m2));

        $this->assertTrue($m2->equals($m2));
        $this->assertFalse($m3->equals($m2));

        $this->assertTrue($m3->greaterThan($m2));
        $this->assertFalse($m2->greaterThan($m3));

        $this->assertTrue($m2->greaterThanOrEqual($m2));
        $this->assertFalse($m2->greaterThanOrEqual($m3));

        $this->assertTrue($m2->lessThan($m3));
        $this->assertFalse($m3->lessThan($m2));

        $this->assertTrue($m2->lessThanOrEqual($m2));
        $this->assertFalse($m3->lessThanOrEqual($m2));
    }

    /**
     * @expectedException \Clicknow\Money\Exceptions\MoneyException
     */
    public function testDifferentCurrenciesCannotBeCompared()
    {
        $m1 = new Money(100, new Currency('BRL'));
        $m2 = new Money(100, new Currency('USD'));

        $m1->compare($m2);
    }

    public function testConversion()
    {
        $m1 = new Money(100, new Currency('BRL'));
        $m2 = new Money(350, new Currency('USD'));

        $this->assertEquals($m1->convert(new Currency('USD'), 3.5), $m2);
    }

    public function testAddition()
    {
        $m1 = new Money(1100.101, new Currency('BRL'));
        $m2 = new Money(1100.021, new Currency('BRL'));
        $sum = $m1->add($m2);
        $this->assertEquals(new Money(2200.122, new Currency('BRL')), $sum);
        $this->assertNotEquals($sum, $m1);
        $this->assertNotEquals($sum, $m2);
    }

    /**
     * @expectedException \Clicknow\Money\Exceptions\MoneyException
     */
    public function testDifferentCurrenciesCannotBeAdded()
    {
        $m1 = new Money(100, new Currency('BRL'));
        $m2 = new Money(100, new Currency('USD'));

        $m1->add($m2);
    }

    public function testSubtraction()
    {
        $m1 = new Money(100.10, new Currency('BRL'));
        $m2 = new Money(100.02, new Currency('BRL'));
        $diff = $m1->subtract($m2);

        $this->assertEquals(new Money(0.08, new Currency('BRL')), $diff);
        $this->assertNotSame($diff, $m1);
        $this->assertNotSame($diff, $m2);
    }

    /**
     * @expectedException \Clicknow\Money\Exceptions\MoneyException
     */
    public function testDifferentCurrenciesCannotBeSubtracted()
    {
        $m1 = new Money(100, new Currency('BRL'));
        $m2 = new Money(100, new Currency('USD'));

        $m1->subtract($m2);
    }

    public function testMultiplication()
    {
        $m1 = new Money(15, new Currency('BRL'));
        $m2 = new Money(1, new Currency('BRL'));

        $this->assertEquals($m1, $m2->multiply(15));
        $this->assertNotEquals($m1, $m2->multiply(10));
    }

    public function testDivision()
    {
        $m1 = new Money(3, new Currency('BRL'));
        $m2 = new Money(10, new Currency('BRL'));

        $this->assertEquals($m1, $m2->divide(3));
        $this->assertNotEquals($m1, $m2->divide(2));
    }

    public function testAllocation()
    {
        $m1 = new Money(100, new Currency('BRL'));

        list($part1, $part2, $part3) = $m1->allocate([1, 1, 1]);
        $this->assertEquals(new Money(34, new Currency('BRL')), $part1);
        $this->assertEquals(new Money(33, new Currency('BRL')), $part2);
        $this->assertEquals(new Money(33, new Currency('BRL')), $part3);

        $m2 = new Money(101, new Currency('BRL'));

        list($part1, $part2, $part3) = $m2->allocate([1, 1, 1]);
        $this->assertEquals(new Money(34, new Currency('BRL')), $part1);
        $this->assertEquals(new Money(34, new Currency('BRL')), $part2);
        $this->assertEquals(new Money(33, new Currency('BRL')), $part3);
    }

    public function testAllocationOrderIsImportant()
    {
        $m = new Money(5, new Currency('BRL'));

        list($part1, $part2) = $m->allocate([3, 7]);
        $this->assertEquals(new Money(2, new Currency('BRL')), $part1);
        $this->assertEquals(new Money(3, new Currency('BRL')), $part2);

        list($part1, $part2) = $m->allocate([7, 3]);
        $this->assertEquals(new Money(4, new Currency('BRL')), $part1);
        $this->assertEquals(new Money(1, new Currency('BRL')), $part2);
    }

    public function testComparators()
    {
        $m1 = new Money(0, new Currency('BRL'));
        $m2 = new Money(-1, new Currency('BRL'));
        $m3 = new Money(1, new Currency('BRL'));
        $m4 = new Money(1, new Currency('BRL'));
        $m5 = new Money(1, new Currency('BRL'));
        $m6 = new Money(-1, new Currency('BRL'));

        $this->assertTrue($m1->isZero());
        $this->assertTrue($m2->isNegative());
        $this->assertTrue($m3->isPositive());
        $this->assertFalse($m4->isZero());
        $this->assertFalse($m5->isNegative());
        $this->assertFalse($m6->isPositive());
    }

    /**
     * @dataProvider providesFormatLocale
     */
    public function testFormatLocale($expected, $cur, $amount, $locale, $message)
    {
        $this->assertEquals($expected, Money::$cur($amount)->formatLocale($locale), $message);
    }

    public function providesFormatLocale()
    {
        return [
            ['R$1.548,48', 'BRL', 154848.25895, 'pt_BR', 'Example: '.__LINE__],
            //['BR$1,548.48', 'BRL', 154848.25895, 'en_US', 'Example: ' . __LINE__],
            ['US$0,48', 'USD', 48.25, 'pt_BR', 'Example: '.__LINE__],
            ['$1,548.48', 'USD', 154848.25895, 'en_US', 'Example: '.__LINE__],
        ];
    }

    public function testFormatSimple()
    {
        $m = new Money(100000, new Currency('BRL'));

        $this->assertEquals('1.000,00', $m->formatSimple());
        $this->assertEquals('1,000.00', $m->formatSimple(2, '.', ','));
        $this->assertEquals('1.000,00', $m->formatSimple(2, ',', '.'));
        $this->assertEquals('1.000', $m->formatSimple(0));
        $this->assertEquals('1.000,000', $m->formatSimple(3));
    }

    /**
     * @dataProvider providesFormat
     */
    public function testFormat($expected, $cur, $amount, $message)
    {
        $this->assertEquals($expected, (string) Money::$cur($amount), $message);
    }

    public function providesFormat()
    {
        return [
            ['R$ 1.548,48', 'BRL', 154848.25895, 'Example: '.__LINE__],
            ['R$ 1.548,48', 'BRL', 154848.25895, 'Example: '.__LINE__],
            ['$ 0.48', 'USD', 48.25, 'Example: '.__LINE__],
            ['$ 1,548.48', 'USD', 154848.25895, 'Example: '.__LINE__],
        ];
    }
}
