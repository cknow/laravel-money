<?php

namespace Clicknow\Money;

use Illuminate\View\Compilers\BladeCompiler;

class BladeExtensions
{
    /**
     * Register.
     *
     * @param \Illuminate\View\Compilers\BladeCompiler $compiler
     *
     * @return void
     */
    public static function register(BladeCompiler $compiler)
    {
        $compiler->directive('money', function ($expression) {
            return "<?php echo money{$expression}; ?>";
        });

        $compiler->directive('currency', function ($expression) {
            return "<?php echo currency{$expression}; ?>";
        });
    }
}
