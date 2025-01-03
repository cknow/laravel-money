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

    public function test_contains()
    {
        $currencies = new ISOCurrencies;
        static::assertTrue($currencies->contains(new \Money\Currency('EUR')));
        static::assertTrue($currencies->contains(new \Money\Currency('USD')));
    }

    public function test_subunit_for()
    {
        $currencies = new ISOCurrencies;
        static::assertEquals(2, $currencies->subunitFor(new \Money\Currency('EUR')));
        static::assertEquals(2, $currencies->subunitFor(new \Money\Currency('USD')));
    }

    public function test_subunit_for_invalid_currency()
    {
        $this->expectException(\Money\Exception\UnknownCurrencyException::class);
        $this->expectExceptionMessage('Cannot find ISO currency XYZ');

        $currencies = new ISOCurrencies;
        $currencies->subunitFor(new \Money\Currency('XYZ'));
    }

    public function test_numeric_code_for()
    {
        $currencies = new ISOCurrencies;
        static::assertEquals(978, $currencies->numericCodeFor(new \Money\Currency('EUR')));
        static::assertEquals(840, $currencies->numericCodeFor(new \Money\Currency('USD')));
    }

    public function test_numeric_code_for_invalid_currency()
    {
        $this->expectException(\Money\Exception\UnknownCurrencyException::class);
        $this->expectExceptionMessage('Cannot find ISO currency XYZ');

        $currencies = new ISOCurrencies;
        $currencies->numericCodeFor(new \Money\Currency('XYZ'));
    }

    public function test_load_currencies()
    {
        $currencies = new ISOCurrencies;
        static::assertContainsOnlyInstancesOf(\Money\Currency::class, $currencies->getIterator());
    }

    public function test_get_currencies()
    {
        $currencies = new ISOCurrencies;

        static::assertIsArray($currencies->getCurrencies());
        static::assertArrayHasKey('EUR', $currencies->getCurrencies());
        static::assertArrayHasKey('USD', $currencies->getCurrencies());
        static::assertArrayNotHasKey('XYZ', $currencies->getCurrencies());
    }

    public function test_invalid_config_currencies_path()
    {
        config(['money.isoCurrenciesPath' => null]);

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Failed to load currency ISO codes.');

        $currencies = new ISOCurrencies;

        $reflection = new \ReflectionObject($currencies);
        $property = $reflection->getProperty('currencies');
        $property->setAccessible(true);
        $property->setValue($currencies, null);

        $currencies->getCurrencies();
    }
}
