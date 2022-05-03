<?php

namespace Cknow\Money\Tests\Rules;

use Cknow\Money\Rules\Currency;
use Cknow\Money\Tests\TestCase;
use Illuminate\Support\Facades\Validator;

class CurrencyTest extends TestCase
{
    public function testValidationPasses()
    {
        $v = Validator::make(
            [
                'currency1' => 'USD',
                'currency2' => 'EUR',
                'currency3' => new \Money\Currency('BRL'),
            ],
            [
                'currency1' =>  'currency',
                'currency2' =>  new Currency,
                'currency3' =>  new Currency,
            ]
        );

        $this->assertFalse($v->fails());
    }

    public function testValidationFails()
    {
        $v = Validator::make(
            [
                'currency1' => 'foo',
                'currency2' => 'bar',
            ],
            [
                'currency1' =>  'currency',
                'currency2' =>  new Currency,
            ]
        );

        $this->assertTrue($v->fails());
        $this->assertEquals(['validation.currency'], $v->errors()->get('currency1'));
        $this->assertEquals(['The currency2 is not a valid currency.'], $v->errors()->get('currency2'));
    }
}
