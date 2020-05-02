<?php

namespace Cknow\Money;

use Cknow\Money\Database\Models\User;
use GrahamCampbell\TestBench\AbstractPackageTestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use InvalidArgumentException;
use Money\Exception\ParserException;
use stdClass;

/**
 * The money cast test.
 *
 */
class MoneyCastTest extends AbstractPackageTestCase
{
    use RefreshDatabase;

    /**
     * Setup the test environment
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->loadMigrationsFrom(__DIR__ . '/Database/Migrations');
    }

    /**
     * Get the service provider class.
     *
     * @param \Illuminate\Contracts\Foundation\Application $app
     *
     * @return string
     */
    protected function getServiceProviderClass($app)
    {
        return MoneyServiceProvider::class;
    }

    /**
     * @test
     */
    public function castsMoneyWhenRetrievingCastedValues()
    {
        $user = User::create([
            'money' => 1234.56,
            'wage' => 50000,
            'debits' => null,
            'currency' => 'AUD',
        ]);

        $this->assertInstanceOf(Money::class, $user->money);
        $this->assertInstanceOf(Money::class, $user->wage);
        $this->assertNull($user->debits);

        $this->assertSame('123456', $user->money->getAmount());
        $this->assertSame('USD', $user->money->getMoney()->getCurrency()->getCode());

        $this->assertSame('5000000', $user->wage->getAmount());
        $this->assertSame('EUR', $user->wage->getMoney()->getCurrency()->getCode());

        $user->debits = 100.99;

        $this->assertSame('10099', $user->debits->getAmount());
        $this->assertSame('AUD', $user->debits->getMoney()->getCurrency()->getCode());

        $user->save();

        $this->assertSame(1, $user->id);

        $this->assertDatabaseHas('users', [
            'id' => 1,
            'money' => 1234.56,
            'wage' => 50000.00,
            'debits' => 100.99,
            'currency' => 'AUD',
        ]);
    }

    /**
     * @test
     */
    public function castsMoneyWhenSettingCastedValues()
    {
        $user = new User([
            'money' => 0,
            'wage' => '6500000',
            'debits' => null,
            'currency' => 'CAD',
        ]);

        $this->assertSame('0', $user->money->getAmount());
        $this->assertSame('USD', $user->money->getMoney()->getCurrency()->getCode());

        $this->assertSame('6500000', $user->wage->getAmount());
        $this->assertSame('EUR', $user->wage->getMoney()->getCurrency()->getCode());

        $this->assertNull($user->debits);

        $user->money = 100;
        $user->wage = 70500.19;
        $user->debits = '¥213860';

        $this->assertSame('10000', $user->money->getAmount());
        $this->assertSame('USD', $user->money->getMoney()->getCurrency()->getCode());

        $this->assertSame('7050019', $user->wage->getAmount());
        $this->assertSame('EUR', $user->wage->getMoney()->getCurrency()->getCode());

        $this->assertSame('213860', $user->debits->getAmount());
        $this->assertSame('JPY', $user->debits->getMoney()->getCurrency()->getCode());
        $this->assertSame('JPY', $user->currency);

        $user->money = '100,000.22';
        $user->debits = 'Ƀ0.00012345';

        $this->assertSame('10000022', $user->money->getAmount());
        $this->assertSame('USD', $user->money->getMoney()->getCurrency()->getCode());

        $this->assertSame('12345', $user->debits->getAmount());
        $this->assertSame('XBT', $user->debits->getMoney()->getCurrency()->getCode());
        $this->assertSame('XBT', $user->currency);

        $user->save();

        $this->assertSame(1, $user->id);

        $this->assertDatabaseHas('users', [
            'id' => 1,
            'money' => 100000.22,
            'wage' => 70500.19,
            'debits' => 0.00012345,
            'currency' => 'XBT',
        ]);
    }

    /**
     * @test
     */
    public function failsToSetInvalidMoney()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid data provided for Cknow\Money\Database\Models\User::$money');

        new User(['money' => new stdClass]);
    }

    /**
     * @test
     */
    public function failsToParseInvalidMoney()
    {
        $this->expectException(ParserException::class);
        $this->expectExceptionMessage('Unable to parse abc');

        new User(['money' => 'abc']);
    }
}
