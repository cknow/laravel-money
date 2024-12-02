<?php

namespace Cknow\Money\Tests\Currencies;

use Cknow\Money\Currencies\ISOCurrencies;
use Cknow\Money\Tests\TestCase;

class ISOCurrenciesTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
    }

    public function testContains()
    {
        $currencies = new ISOCurrencies();
        static::assertTrue($currencies->contains(new \Money\Currency('EUR')));
        static::assertTrue($currencies->contains(new \Money\Currency('USD')));
    }

    public function testSubunitFor()
    {
        $currencies = new ISOCurrencies();
        static::assertEquals(2, $currencies->subunitFor(new \Money\Currency('EUR')));
        static::assertEquals(2, $currencies->subunitFor(new \Money\Currency('USD')));
    }

    public function testSubunitForInvalidCurrency()
    {
        $this->expectException(\Money\Exception\UnknownCurrencyException::class);
        $this->expectExceptionMessage('Cannot find ISO currency XYZ');

        $currencies = new ISOCurrencies();
        $currencies->subunitFor(new \Money\Currency('XYZ'));
    }

    public function testNumericCodeFor()
    {
        $currencies = new ISOCurrencies();
        static::assertEquals(978, $currencies->numericCodeFor(new \Money\Currency('EUR')));
        static::assertEquals(840, $currencies->numericCodeFor(new \Money\Currency('USD')));
    }

    public function testNumericCodeForInvalidCurrency()
    {
        $this->expectException(\Money\Exception\UnknownCurrencyException::class);
        $this->expectExceptionMessage('Cannot find ISO currency XYZ');

        $currencies = new ISOCurrencies();
        $currencies->numericCodeFor(new \Money\Currency('XYZ'));
    }

    public function testLoadCurrencies()
    {
        $currencies = new ISOCurrencies();
        static::assertContainsOnlyInstancesOf(\Money\Currency::class, $currencies->getIterator());
    }

    public function testGetCurrencies()
    {
        $currencies = new ISOCurrencies();

        static::assertIsArray($currencies->getCurrencies());
        static::assertArrayHasKey('EUR', $currencies->getCurrencies());
        static::assertArrayHasKey('USD', $currencies->getCurrencies());
        static::assertArrayNotHasKey('XYZ', $currencies->getCurrencies());
    }

    public function testInvalidConfigCurrenciesPath()
    {
        config(['money.isoCurrenciesPath' => null]);

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Failed to load currency ISO codes.');

        $currencies = new ISOCurrencies();

        $reflection = new \ReflectionObject($currencies);
        $property = $reflection->getProperty('currencies');
        $property->setAccessible(true);
        $property->setValue($currencies, null);

        $currencies->getCurrencies();
    }
}
