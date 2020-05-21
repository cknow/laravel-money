<?php

namespace Cknow\Money;

class CurrenciesTraitTest extends \PHPUnit\Framework\TestCase
{
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
}
