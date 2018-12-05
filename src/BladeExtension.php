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

        self::registerParsers($compiler);
    }

    /**
     * Register parsers.
     *
     * @param \Illuminate\View\Compilers\BladeCompiler $compiler
     */
    private static function registerParsers(BladeCompiler $compiler)
    {
        $compiler->directive('money_parse', function ($expression) {
            return "<?php echo money_parse(${expression}); ?>";
        });

        $compiler->directive('money_parse_by_bitcoin', function ($expression) {
            return "<?php echo money_parse_by_bitcoin(${expression}); ?>";
        });

        $compiler->directive('money_parse_by_decimal', function ($expression) {
            return "<?php echo money_parse_by_decimal(${expression}); ?>";
        });

        $compiler->directive('money_parse_by_intl', function ($expression) {
            return "<?php echo money_parse_by_intl(${expression}); ?>";
        });

        $compiler->directive('money_parse_by_intl_localized_decimal', function ($expression) {
            return "<?php echo money_parse_by_intl_localized_decimal(${expression}); ?>";
        });
    }
}
