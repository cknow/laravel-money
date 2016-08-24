<?php

namespace ClickNow\Money;

use Illuminate\Support\Str;
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

            if (Str::startsWith($expression, '(')) {
                $expression = substr($expression, 1, -1);
            }

            return "<?php echo money($expression); ?>";
        });

        $compiler->directive('currency', function ($expression) {

            if (Str::startsWith($expression, '(')) {
                $expression = substr($expression, 1, -1);
            }

            return "<?php echo currency($expression); ?>";
        });
    }
}
