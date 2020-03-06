<?php

namespace Cknow\Money;

use Money\Currencies\BitcoinCurrencies;
use Money\Currencies\ISOCurrencies;
use Money\Formatter\BitcoinMoneyFormatter;
use Money\Formatter\DecimalMoneyFormatter;
use Money\Formatter\IntlLocalizedDecimalFormatter;
use Money\Formatter\IntlMoneyFormatter;
use NumberFormatter as N;

/**
 * @covers \Cknow\Money\MoneyFormatterTrait
 */
class MoneyFormatterTraitTest extends \PHPUnit\Framework\TestCase
{
    protected function setUp(): void
    {
        Money::setCurrencies(new ISOCurrencies());
        Money::setLocale('en_US');
    }

    public function testFormat()
    {
        static::assertEquals('$1.00', Money::USD(100)->format());
        static::assertEquals('€1.00', Money::EUR(100)->format());
        static::assertEquals('€1.00', Money::EUR(100)->format('en_US'));
        static::assertEquals('$1.00', Money::USD(100)->format('en_US', Money::getCurrencies(), N::CURRENCY));
        static::assertEquals('1,99', Money::EUR(199)->format('fr_FR', Money::getCurrencies(), N::DECIMAL));
        static::assertEquals('1', Money::USD(100)->format('en_US', Money::getCurrencies(), N::DECIMAL));
    }

    public function testFormatByAggregate()
    {
        $formatters = [
            'XBT' => new BitcoinMoneyFormatter(2, new BitcoinCurrencies()),
            'EUR' => new DecimalMoneyFormatter(Money::getCurrencies()),
            'USD' => new IntlLocalizedDecimalFormatter(new N('en_US', N::DECIMAL), Money::getCurrencies()),
            'BRL' => new IntlMoneyFormatter(new N('pt_BR', N::DECIMAL), Money::getCurrencies()),
        ];

        static::assertEquals("\xC9\x831000.00", Money::XBT(100000000000)->formatByAggregate($formatters));
        static::assertEquals('1.00', Money::EUR(100)->formatByAggregate($formatters));
        static::assertEquals('1', Money::USD(100)->formatByAggregate($formatters));
        static::assertEquals('5', Money::BRL(500)->formatByAggregate($formatters));
    }

    public function testFormatByBitcoin()
    {
        static::assertEquals("\xC9\x835", Money::XBT(500000000)->formatByBitcoin(0));
        static::assertEquals("\xC9\x830.41", Money::XBT(41000000)->formatByBitcoin(2));
        static::assertEquals("\xC9\x8310.0000", Money::XBT(1000000000)->formatByBitcoin(4, new BitcoinCurrencies()));
    }

    public function testFormatByDecimal()
    {
        static::assertEquals('1.00', Money::USD(100)->formatByDecimal(Money::getCurrencies()));
        static::assertEquals('1.00', Money::USD(100)->formatByDecimal());
    }

    public function testFormatByIntl()
    {
        static::assertEquals('$1.00', Money::USD(100)->formatByIntl());
        static::assertEquals('€1.00', Money::EUR(100)->formatByIntl());
        static::assertEquals('€1.00', Money::EUR(100)->formatByIntl('en_US'));
        static::assertEquals('$1.00', Money::USD(100)->formatByIntl('en_US', Money::getCurrencies(), N::CURRENCY));
        static::assertEquals('1,99', Money::EUR(199)->formatByIntl('fr_FR', Money::getCurrencies(), N::DECIMAL));
        static::assertEquals('1', Money::USD(100)->formatByIntl('en_US', Money::getCurrencies(), N::DECIMAL));
    }

    public function testFormatByIntlLocalizedDecimal()
    {
        static::assertEquals(
            '$1.00',
            Money::USD(100)->formatByIntlLocalizedDecimal()
        );
        static::assertEquals(
            '$1.00',
            Money::USD(100)->formatByIntlLocalizedDecimal('en_US', Money::getCurrencies(), N::CURRENCY)
        );
        static::assertEquals(
            '1,99',
            Money::EUR(199)->formatByIntlLocalizedDecimal('fr_FR', Money::getCurrencies(), N::DECIMAL)
        );
        static::assertEquals(
            '1',
            Money::USD(100)->formatByIntlLocalizedDecimal('en_US', Money::getCurrencies(), N::DECIMAL)
        );
    }

    public function testFormatByFormatter()
    {
        $formatter = new DecimalMoneyFormatter(Money::getCurrencies());

        static::assertEquals('1.00', Money::USD(100)->formatByFormatter($formatter));
    }
}
