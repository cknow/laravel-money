<?php

namespace Cknow\Money;

use Illuminate\View\Compilers\BladeCompiler;
use Mockery;

/**
 * @covers \Cknow\Money\BladeExtension
 */
class BladeExtensionTest extends \PHPUnit_Framework_TestCase
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

    public function testCurrency()
    {
        $this->assertEquals(
            '<?php echo currency("BRL"); ?>',
            $this->compiler->compileString('@currency("BRL")')
        );
    }
}
