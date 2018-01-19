<?php

namespace Cknow\Money;

use Illuminate\View\Compilers\BladeCompiler;

class BladeExtension
{
    /**
     * Register.
     *
     * @param \Illuminate\View\Compilers\BladeCompiler $compiler
     */
    public static function register(BladeCompiler $compiler)
    {
        $compiler->directive('currency', function ($expression) {
            return "<?php echo currency(${expression}); ?>";
        });

        $compiler->directive('money', function ($expression) {
            return "<?php echo money(${expression}); ?>";
        });

        $compiler->directive('money_parse', function ($expression) {
            return "<?php echo money_parse(${expression}); ?>";
        });
    }
}
