<?php

namespace Cknow\Money;

use Illuminate\View\Compilers\BladeCompiler;

class BladeExtensions
{
    /**
     * Register.
     *
     * @param \Illuminate\View\Compilers\BladeCompiler $compiler
     */
    public static function register(BladeCompiler $compiler)
    {
        $compiler->directive('money', function ($expression) {
            return "<?php echo money($expression); ?>";
        });

        $compiler->directive('currency', function ($expression) {
            return "<?php echo currency($expression); ?>";
        });
    }
}
