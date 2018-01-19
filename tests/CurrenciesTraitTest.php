<?php

namespace Cknow\Money;

/**
 * @covers \Cknow\Money\CurrenciesTrait
 */
class CurrenciesTraitTest extends \PHPUnit\Framework\TestCase
{
    public function testGetCurrencies()
    {
        $this->assertInstanceOf(\Money\Currencies::class, CurrenciesTrait::getCurrencies());
        $this->assertInstanceOf(\Money\Currencies\ISOCurrencies::class, CurrenciesTrait::getCurrencies());
    }

    public function testSetCurrencies()
    {
        CurrenciesTrait::setCurrencies(new \Money\Currencies\BitcoinCurrencies());

        $this->assertInstanceOf(\Money\Currencies::class, CurrenciesTrait::getCurrencies());
        $this->assertInstanceOf(\Money\Currencies\BitcoinCurrencies::class, CurrenciesTrait::getCurrencies());
    }
}
