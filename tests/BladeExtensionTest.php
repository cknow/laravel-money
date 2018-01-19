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

    public function tearDown()
    {
        Mockery::close();
    }

    public function testCurrency()
    {
        $this->assertEquals(
            '<?php echo currency("BRL"); ?>',
            $this->compiler->compileString('@currency("BRL")')
        );
    }

    public function testMoney()
    {
        $this->assertEquals(
            '<?php echo money(500); ?>',
            $this->compiler->compileString('@money(500)')
        );

        $this->assertEquals(
            '<?php echo money(500, "USD"); ?>',
            $this->compiler->compileString('@money(500, "USD")')
        );
    }

    public function testMoneyParse()
    {
        $this->assertEquals(
            '<?php echo money_parse("R$5,00"); ?>',
            $this->compiler->compileString('@money_parse("R$5,00")')
        );

        $this->assertEquals(
            '<?php echo money_parse("R$5,00", "BRL"); ?>',
            $this->compiler->compileString('@money_parse("R$5,00", "BRL")')
        );

        $this->assertEquals(
            '<?php echo money_parse("$5.00", "USD", "en_US"); ?>',
            $this->compiler->compileString('@money_parse("$5.00", "USD", "en_US")')
        );
    }
}
