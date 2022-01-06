<?php

namespace Cknow\Money\Tests;

use Cknow\Money\CurrenciesTrait;
use Money\Currency;
use stdClass;

class CurrenciesTraitTest extends TestCase
{
    private static function currencyListSize(\Money\Currencies $currencies)
    {
        $size = 0;
        foreach ($currencies as $currency) {
            $size++;
        }

        return $size;
    }

    public function testIsValidCurrency()
    {
        $mock = $this->getMockForTrait(CurrenciesTrait::class);

        static::assertTrue($mock->isValidCurrency('USD'));
        static::assertTrue($mock->isValidCurrency(new Currency('USD')));
    }

    public function testIsNotValidCurrency()
    {
        $mock = $this->getMockForTrait(CurrenciesTrait::class);

        static::assertFalse($mock->isValidCurrency('FAIL'));
        static::assertFalse($mock->isValidCurrency(new Currency('FAIL')));
    }

    public function testGetCurrencies()
    {
        $mock = $this->getMockForTrait(CurrenciesTrait::class);

        static::assertInstanceOf(\Money\Currencies::class, $mock->getCurrencies());
        static::assertInstanceOf(\Money\Currencies\AggregateCurrencies::class, $mock->getCurrencies());
    }

    public function testSetCurrencies()
    {
        $mock = $this->getMockForTrait(CurrenciesTrait::class);
        $mock->setCurrencies(new \Money\Currencies\BitcoinCurrencies());

        static::assertInstanceOf(\Money\Currencies::class, $mock->getCurrencies());
        static::assertInstanceOf(\Money\Currencies\BitcoinCurrencies::class, $mock->getCurrencies());
    }

    public function testSetCurrenciesCustomCurrencies()
    {
        $mock = $this->getMockForTrait(CurrenciesTrait::class);
        $mock->setCurrencies([
            'iso' => ['USD'],
            'bitcoin' => ['XBT'],
            'custom' => [
                'MY1' => 2,
                'MY2' => 3,
            ],
        ]);

        static::assertInstanceOf(\Money\Currencies\AggregateCurrencies::class, $mock->getCurrencies());
        static::assertContainsOnlyInstancesOf(Currency::class, $mock->getCurrencies());
        static::assertEquals(4, static::currencyListSize($mock->getCurrencies()));
        static::assertTrue($mock->getCurrencies()->contains(new Currency('USD')));
        static::assertTrue($mock->getCurrencies()->contains(new Currency('XBT')));
        static::assertTrue($mock->getCurrencies()->contains(new Currency('MY1')));
        static::assertTrue($mock->getCurrencies()->contains(new Currency('MY2')));
        static::assertEquals(2, $mock->getCurrencies()->subunitFor(new Currency('MY1')));
        static::assertEquals(3, $mock->getCurrencies()->subunitFor(new Currency('MY2')));
    }

    public function testSetCurrenciesWrongISOCurrencyCode()
    {
        $this->expectException(\InvalidArgumentException::class);
        $mock = $this->getMockForTrait(CurrenciesTrait::class);
        $mock->setCurrencies([
            'iso' => ['UNKNOWN'],
            'bitcoin' => ['XBT'],
            'custom' => [
                'MY1' => 2,
                'MY2' => 3,
            ],
        ]);
    }

    public function testSetCurrenciesWrongBitcoinCurrencyCode()
    {
        $this->expectException(\InvalidArgumentException::class);
        $mock = $this->getMockForTrait(CurrenciesTrait::class);
        $mock->setCurrencies([
            'iso' => ['USD'],
            'bitcoin' => ['UNKNOWN'],
            'custom' => [
                'MY1' => 2,
                'MY2' => 3,
            ],
        ]);
    }

    public function testSetCurrenciesWrongStandardCurrenciesConfig()
    {
        $this->expectException(\InvalidArgumentException::class);
        $mock = $this->getMockForTrait(CurrenciesTrait::class);
        $mock->setCurrencies([
            'iso' => new stdClass(),
            'bitcoin' => 'all',
        ]);
    }

    public function testSetCurrenciesEmptyCurrenciesConfig()
    {
        $mock = $this->getMockForTrait(CurrenciesTrait::class);
        $mock->setCurrencies([
            'iso' => [],
            'bitcoin' => [],
            'custom' => [],
        ]);

        static::assertInstanceOf(\Money\Currencies::class, $mock->getCurrencies());
        static::assertEquals(0, static::currencyListSize($mock->getCurrencies()));
    }

    public function testSetCurrenciesDefaultCurrenciesConfig()
    {
        $mock = $this->getMockForTrait(CurrenciesTrait::class);
        $mock->setCurrencies([]);

        static::assertInstanceOf(\Money\Currencies\ISOCurrencies::class, $mock->getCurrencies());
    }

    public function testSetCurrenciesAllISOCurrencies()
    {
        $mock = $this->getMockForTrait(CurrenciesTrait::class);
        $mock->setCurrencies([
            'iso' => 'all',
        ]);

        static::assertEquals(
            new \Money\Currencies\AggregateCurrencies([new \Money\Currencies\ISOCurrencies()]),
            $mock->getCurrencies()
        );
    }

    public function testSetCurrenciesAllBitcoinCurrencies()
    {
        $mock = $this->getMockForTrait(CurrenciesTrait::class);
        $mock->setCurrencies([
            'bitcoin' => 'all',
        ]);

        static::assertEquals(
            new \Money\Currencies\AggregateCurrencies([new \Money\Currencies\BitcoinCurrencies()]),
            $mock->getCurrencies()
        );
    }
}
