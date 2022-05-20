<?php

namespace Cknow\Money\Tests\Rules;

use Cknow\Money\Rules\Money;
use Cknow\Money\Tests\TestCase;
use Illuminate\Support\Facades\Validator;
use stdClass;

class MoneyTest extends TestCase
{
    public function testValidationPasses()
    {
        $v = Validator::make(
            [
                'money1' => '10',
                'money2' => 10,
                'money3' => 10.00,
                'money4' => 10.10,
                'money5' => '10.10',
                'money6' => '$10.10',
                'money7' => '$10.10',
                'money8' => 'R$10,00',
                'money9' => 'R$10,00',
                'money10' => \Money\Money::USD(10),
                'money11' => new \Cknow\Money\Money(10),
            ],
            [
                'money1' => 'money',
                'money2' => new Money,
                'money3' => new Money,
                'money4' => new Money,
                'money5' => new Money,
                'money6' => new Money,
                'money7' => new Money('USD'),
                'money8' => new Money('BRL', 'pt_BR'),
                'money9' => 'money:BRL,pt_BR',
                'money10' => new Money,
                'money11' => new Money,
            ]
        );

        $this->assertFalse($v->fails());
    }

    public function testValidationFails()
    {
        $v = Validator::make(
            [
                'money1' => 'foo',
                'money2' => '$ 100,00',
                'money3' => '$ 100, 00',
                'money4' => 'R$ 1,00.000',
                'money5' => 'R$ 1,.00',
                'money6' => '$1.00',
                'money7' => '$1.00',
                'money8' => new stdClass,
            ],
            [
                'money1' => 'money',
                'money2' => new Money,
                'money3' => new Money,
                'money4' => new Money,
                'money5' => new Money,
                'money6' => new Money('BRL', 'pt_BR'),
                'money7' => 'money:BRL,pt_BR',
                'money8' => new Money,
            ]
        );

        $this->assertTrue($v->fails());
        $this->assertEquals(['validation.money'], $v->errors()->get('money1'));
        $this->assertEquals(['The money2 is not a valid money.'], $v->errors()->get('money2'));
        $this->assertEquals(['The money3 is not a valid money.'], $v->errors()->get('money3'));
        $this->assertEquals(['The money4 is not a valid money.'], $v->errors()->get('money4'));
        $this->assertEquals(['The money5 is not a valid money.'], $v->errors()->get('money5'));
        $this->assertEquals(['The money6 is not a valid money.'], $v->errors()->get('money6'));
        $this->assertEquals(['validation.money'], $v->errors()->get('money7'));
        $this->assertEquals(['The money8 is not a valid money.'], $v->errors()->get('money8'));
    }
}
