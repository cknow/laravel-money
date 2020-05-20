<?php

namespace Cknow\Money;

class CurrenciesTraitTest extends \PHPUnit\Framework\TestCase
{
    public function testGetCurrencies()
    {
        static::assertInstanceOf(\Money\Currencies::class, CurrenciesTrait::getCurrencies());
        static::assertInstanceOf(\Money\Currencies\ISOCurrencies::class, CurrenciesTrait::getCurrencies());
    }

    public function testSetCurrencies()
    {
        CurrenciesTrait::setCurrencies(new \Money\Currencies\BitcoinCurrencies());

        static::assertInstanceOf(\Money\Currencies::class, CurrenciesTrait::getCurrencies());
        static::assertInstanceOf(\Money\Currencies\BitcoinCurrencies::class, CurrenciesTrait::getCurrencies());
    }
}
