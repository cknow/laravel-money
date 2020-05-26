<?php

namespace Cknow\Money;

class CurrenciesTraitTest extends \PHPUnit\Framework\TestCase
{
    private static function currencyListSize(\Money\Currencies\CurrencyList $currencies)
    {
        $size = 0;
        foreach ($currencies as $currency) {
            $size++;
        }
        return $size;
    }

    public function testGetCurrencies()
    {
        $mock = $this->getMockForTrait(CurrenciesTrait::class);

        static::assertInstanceOf(\Money\Currencies::class, $mock->getCurrencies());
        static::assertInstanceOf(\Money\Currencies\ISOCurrencies::class, $mock->getCurrencies());
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
            'iso' => 'USD',
            'bitcoin' => 'XBT',
            'custom' => [
                'MY1' => 2,
                'MY2' => 3
            ]
        ]);

        static::assertInstanceOf(\Money\Currencies\AggregateCurrencies::class, $mock->getCurrencies());
        static::assertContainsOnlyInstancesOf(\Money\Currency::class, $mock->getCurrencies());
        static::assertEquals(4, static::currencyListSize($mock->getCurrencies()));
        static::assertTrue($mock->getCurrencies()->contains(new \Money\Currency('USD')));
        static::assertTrue($mock->getCurrencies()->contains(new \Money\Currency('XBT')));
        static::assertTrue($mock->getCurrencies()->contains(new \Money\Currency('MY1')));
        static::assertTrue($mock->getCurrencies()->contains(new \Money\Currency('MY2')));
        static::assertEquals(2, $mock->getCurrencies()->subunitFor(new \Money\Currency('MY1')));
        static::assertTrue(3, $mock->getCurrencies()->subunitFor(new \Money\Currency('MY2')));
    }

    public function testSetCurrenciesWrongISOCurrencyCode()
    {
        $this->expectException(\InvalidArgumentException::class);
        $mock = $this->getMockForTrait(CurrenciesTrait::class);
        $mock->setCurrencies([
            'iso' => 'UNKNOWN',
            'bitcoin' => 'XBT',
            'custom' => [
                'MY1' => 2,
                'MY2' => 3
            ]
        ]);
    }

    public function testSetCurrenciesWrongBitcoinCurrencyCode()
    {
        $this->expectException(\InvalidArgumentException::class);
        $mock = $this->getMockForTrait(CurrenciesTrait::class);
        $mock->setCurrencies([
            'iso' => 'USD',
            'bitcoin' => 'UNKNOWN',
            'custom' => [
                'MY1' => 2,
                'MY2' => 3
            ]
        ]);
    }

    public function testSetCurrenciesWrongCustomCurrenciesConfig()
    {
        $this->expectException(\InvalidArgumentException::class);
        $mock = $this->getMockForTrait(CurrenciesTrait::class);
        $mock->setCurrencies([
            'iso' => 'USD',
            'bitcoin' => 'XBT',
            'custom' => [
                'MY1' => ['some' => 'value'],
                'MY2' => -5
            ]
        ]);
    }

    public function testSetCurrenciesWrongCurrenciesConfig()
    {
        $this->expectException(\InvalidArgumentException::class);
        $mock = $this->getMockForTrait(CurrenciesTrait::class);
        $mock->setCurrencies(5);
    }

    public function testSetCurrenciesEmptyCurrenciesConfig()
    {
        $this->expectException(\InvalidArgumentException::class);
        $mock = $this->getMockForTrait(CurrenciesTrait::class);
        $mock->setCurrencies([
            'iso' => [],
            'bitcoin' => [],
            'custom' => []
        ]);

        static::assertInstanceOf(\Money\Currencies::class, $mock->getCurrencies());
        static::assertEquals(0, static::currencyListSize($mock->getCurrencies()));
    }

    public function testSetCurrenciesDefaultCurrenciesConfig()
    {
        $this->expectException(\InvalidArgumentException::class);
        $mock = $this->getMockForTrait(CurrenciesTrait::class);
        $mock->setCurrencies([]);

        static::assertInstanceOf(\Money\Currencies\ISOCurrencies::class, $mock->getCurrencies());
    }

    public function testSetCurrenciesAllISOCurrencies()
    {
        $this->expectException(\InvalidArgumentException::class);
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
        $this->expectException(\InvalidArgumentException::class);
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
