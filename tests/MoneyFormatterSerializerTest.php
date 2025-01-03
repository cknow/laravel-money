<?php

namespace Cknow\Money\Tests;

use Cknow\Money\Money;
use Cknow\Money\Serializer\ArrayMoneySerializer;
use Cknow\Money\Serializer\DecimalMoneySerializer;
use Cknow\Money\Serializer\IntegerMoneySerializer;
use Cknow\Money\Serializer\StringMoneySerializer;
use InvalidArgumentException;

class MoneyFormatterSerializerTest extends TestCase
{
    public function test_serialize()
    {
        static::assertEquals([
            'amount' => '100',
            'currency' => 'USD',
            'formatted' => '$1.00',
        ], Money::USD(100)->serialize());
        static::assertEquals([
            'amount' => '100',
            'currency' => 'EUR',
            'formatted' => '€1.00',
        ], Money::EUR(100)->serialize());
        static::assertEquals([
            'amount' => '199',
            'currency' => 'EUR',
            'formatted' => '€1.99',
        ], Money::EUR(199)->serialize());
    }

    public function test_default_serializer()
    {
        config(['money.defaultSerializer' => null]);
        static::assertEquals([
            'amount' => '100',
            'currency' => 'USD',
            'formatted' => '$1.00',
        ], Money::USD(100)->serialize());

        config(['money.defaultSerializer' => ArrayMoneySerializer::class]);
        static::assertEquals([
            'amount' => '100',
            'currency' => 'USD',
            'formatted' => '$1.00',
        ], Money::USD(100)->serialize());

        config(['money.defaultSerializer' => DecimalMoneySerializer::class]);
        static::assertEquals('1.00', Money::USD(100)->serialize());

        config(['money.defaultSerializer' => IntegerMoneySerializer::class]);
        static::assertEquals(100, Money::USD(100)->serialize());

        config(['money.defaultSerializer' => StringMoneySerializer::class]);
        static::assertEquals('$1.00', Money::USD(100)->serialize());

        config(['money.defaultSerializer' => null]);
        static::assertEquals([
            'amount' => '100',
            'currency' => 'EUR',
            'formatted' => '€1.00',
        ], Money::EUR(100)->serialize());

        config(['money.defaultSerializer' => ArrayMoneySerializer::class]);
        static::assertEquals([
            'amount' => '100',
            'currency' => 'EUR',
            'formatted' => '€1.00',
        ], Money::EUR(100)->serialize());

        config(['money.defaultSerializer' => [DecimalMoneySerializer::class, []]]);
        static::assertEquals('1.00', Money::EUR(100)->serialize());

        config(['money.defaultSerializer' => [IntegerMoneySerializer::class, []]]);
        static::assertEquals(100, Money::EUR(100)->serialize());

        config(['money.defaultSerializer' => [StringMoneySerializer::class, []]]);
        static::assertEquals('€1.00', Money::EUR(100)->serialize());
    }

    public function test_invalid_default_serializer()
    {
        $defaultSerializer = [BitcoinMoneySerializer::class];

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid default serializer '.json_encode($defaultSerializer));

        config(['money.defaultSerializer' => $defaultSerializer]);
        static::assertEquals('$1.00', Money::USD(100)->serialize());
    }

    public function test_serialize_by_array()
    {
        static::assertEquals([
            'amount' => '100',
            'currency' => 'USD',
            'formatted' => '$1.00',
        ], Money::USD(100)->serializeByArray());

        static::assertEquals([
            'amount' => '100',
            'currency' => 'EUR',
            'formatted' => '€1.00',
        ], Money::EUR(100)->serializeByArray());
    }

    public function test_serialize_by_serializer_with_array()
    {
        static::assertEquals([
            'amount' => '100',
            'currency' => 'USD',
            'formatted' => '$1.00',
        ], Money::USD(100)->serializeBySerializer(new ArrayMoneySerializer));

        static::assertEquals([
            'amount' => '100',
            'currency' => 'EUR',
            'formatted' => '€1.00',
        ], Money::EUR(100)->serializeBySerializer(new ArrayMoneySerializer));
    }

    public function test_serialize_by_decimal()
    {
        static::assertEquals('1.00', Money::USD(100)->serializeBySerializer(new DecimalMoneySerializer));

        static::assertEquals('1.00', Money::EUR(100)->serializeBySerializer(new DecimalMoneySerializer));
    }

    public function test_serialize_by_integer()
    {
        static::assertEquals(100, Money::USD(100)->serializeBySerializer(new IntegerMoneySerializer));

        static::assertEquals(100, Money::EUR(100)->serializeBySerializer(new IntegerMoneySerializer));
    }

    public function test_serialize_by_string()
    {
        static::assertEquals('$1.00', Money::USD(100)->serializeBySerializer(new StringMoneySerializer));

        static::assertEquals('€1.00', Money::EUR(100)->serializeBySerializer(new StringMoneySerializer));
    }
}
