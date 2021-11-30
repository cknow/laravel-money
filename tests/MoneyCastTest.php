<?php

namespace Cknow\Money\Tests;

use Cknow\Money\Money;
use Cknow\Money\Tests\Database\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use InvalidArgumentException;
use Money\Exception\ParserException;
use Money\Money as BaseMoney;
use stdClass;

/**
 * The money cast test.
 */
class MoneyCastTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Setup the test environment.
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->loadMigrationsFrom(__DIR__.'/Database/Migrations');

        Money::setCurrencies(config('money.currencies'));
    }

    public function testCastsMoneyWhenRetrievingCastedValues()
    {
        $user = User::create([
            'money' => 1234.56,
            'wage' => 50000,
            'debits' => null,
            'credits' => null,
            'currency' => 'AUD',
        ]);

        static::assertInstanceOf(Money::class, $user->money);
        static::assertInstanceOf(Money::class, $user->wage);
        static::assertNull($user->debits);
        static::assertNull($user->credits);

        static::assertSame('123456', $user->money->getAmount());
        static::assertSame('USD', $user->money->getCurrency()->getCode());

        static::assertSame('50000', $user->wage->getAmount());
        static::assertSame('EUR', $user->wage->getCurrency()->getCode());

        $user->debits = 100.99;
        $user->credits = '$99';

        static::assertSame('10099', $user->debits->getAmount());
        static::assertSame('AUD', $user->debits->getCurrency()->getCode());

        static::assertSame('9900', $user->credits->getAmount());
        static::assertSame('USD', $user->credits->getCurrency()->getCode());

        $user->save();

        static::assertSame(1, $user->id);

        $this->assertDatabaseHas('users', [
            'id' => 1,
            'money' => '$1,234.56',
            'wage' => 50000,
            'debits' => 100.99,
            'credits' => 99,
            'currency' => 'AUD',
        ]);
    }

    public function testCastsMoneyWhenSettingCastedValues()
    {
        $user = new User([
            'money' => 0,
            'wage' => '65000.00',
            'debits' => null,
            'currency' => 'CAD',
        ]);

        static::assertSame('0', $user->money->getAmount());
        static::assertSame('USD', $user->money->getCurrency()->getCode());

        static::assertSame('6500000', $user->wage->getAmount());
        static::assertSame('EUR', $user->wage->getCurrency()->getCode());

        static::assertNull($user->debits);

        $user->money = new BaseMoney(10000, $user->money->getCurrency());

        static::assertSame('10000', $user->money->getAmount());

        $user->money = 100;
        $user->wage = 70500.19;
        $user->debits = '¥213860';

        static::assertSame('100', $user->money->getAmount());
        static::assertSame('USD', $user->money->getCurrency()->getCode());

        static::assertSame('7050019', $user->wage->getAmount());
        static::assertSame('EUR', $user->wage->getCurrency()->getCode());

        static::assertSame('213860', $user->debits->getAmount());
        static::assertSame('JPY', $user->debits->getCurrency()->getCode());
        static::assertSame('JPY', $user->currency);

        $user->money = '100,000.22';
        $user->debits = 'Ƀ0.00012345';

        static::assertSame('10000022', $user->money->getAmount());
        static::assertSame('USD', $user->money->getCurrency()->getCode());

        static::assertSame('12345', $user->debits->getAmount());
        static::assertSame('XBT', $user->debits->getCurrency()->getCode());
        static::assertSame('XBT', $user->currency);

        $user->save();

        static::assertSame(1, $user->id);

        $this->assertDatabaseHas('users', [
            'id' => 1,
            'money' => '$100,000.22',
            'wage' => 7050019,
            'debits' => 0.00012345,
            'currency' => 'XBT',
        ]);
    }

    public function testFailsToSetInvalidMoney()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid data provided for Cknow\Money\Tests\Database\Models\User::$money');

        new User(['money' => new stdClass()]);
    }

    public function testFailsToParseInvalidMoney()
    {
        $this->expectException(ParserException::class);
        $this->expectExceptionMessage('Unable to parse abc');

        new User(['money' => 'abc']);
    }
}
