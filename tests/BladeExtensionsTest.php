<?php

use Illuminate\View\Compilers\BladeCompiler;
use ClickNow\Money\BladeExtensions;

class BladeExtensionsTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var \Illuminate\View\Compilers\BladeCompiler
     */
    protected $compiler;

    protected function setUp()
    {
        parent::setUp();

        $this->compiler = new BladeCompiler(Mockery::mock('Illuminate\Filesystem\Filesystem'), __DIR__);
        BladeExtensions::register($this->compiler);
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
            '<?php echo money(500, "BRL"); ?>',
            $this->compiler->compileString('@money(500, "BRL")')
        );

        $this->assertEquals(
            '<?php echo money(500, "BRL", true); ?>',
            $this->compiler->compileString('@money(500, "BRL", true)')
        );
    }

    public function testCurrency()
    {
        $this->assertEquals('<?php echo currency("BRL"); ?>', $this->compiler->compileString('@currency("BRL")'));
    }
}
