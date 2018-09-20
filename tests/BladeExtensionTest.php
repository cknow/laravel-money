<?php

namespace Cknow\Money;

use Illuminate\View\Compilers\BladeCompiler;
use Mockery;

/**
 * @covers \Cknow\Money\BladeExtension
 */
class BladeExtensionTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var \Illuminate\View\Compilers\BladeCompiler
     */
    protected $compiler;

    protected function setUp()
    {
        parent::setUp();

        $this->compiler = new BladeCompiler(Mockery::mock('Illuminate\Filesystem\Filesystem'), __DIR__);

        BladeExtension::register($this->compiler);
    }

    protected function tearDown()
    {
        Mockery::close();
    }

    public function testCurrency()
    {
        static::assertEquals(
            '<?php echo currency("USD"); ?>',
            $this->compiler->compileString('@currency("USD")')
        );
    }

    public function testMoney()
    {
        static::assertEquals(
            '<?php echo money(500); ?>',
            $this->compiler->compileString('@money(500)')
        );

        static::assertEquals(
            '<?php echo money(500, "USD"); ?>',
            $this->compiler->compileString('@money(500, "USD")')
        );
    }

    public function testMoneyParse()
    {
        static::assertEquals(
            '<?php echo money_parse("R$5,00"); ?>',
            $this->compiler->compileString('@money_parse("R$5,00")')
        );

        static::assertEquals(
            '<?php echo money_parse("$5.00", "USD"); ?>',
            $this->compiler->compileString('@money_parse("$5.00", "USD")')
        );

        static::assertEquals(
            '<?php echo money_parse("$5.00", "USD", "en_US"); ?>',
            $this->compiler->compileString('@money_parse("$5.00", "USD", "en_US")')
        );

        static::assertEquals(
            '<?php echo money_parse("$5.00", "USD", "en_US", null); ?>',
            $this->compiler->compileString('@money_parse("$5.00", "USD", "en_US", null)')
        );
    }

    public function testMoneyParseByDecimal()
    {
        static::assertEquals(
            '<?php echo money_parse_by_decimal("5.00", "USD"); ?>',
            $this->compiler->compileString('@money_parse_by_decimal("5.00", "USD")')
        );

        static::assertEquals(
            '<?php echo money_parse_by_decimal("5.00", "USD", null); ?>',
            $this->compiler->compileString('@money_parse_by_decimal("5.00", "USD", null)')
        );
    }
}
