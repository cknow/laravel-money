<?php

namespace Cknow\Money;

use Illuminate\Support\ServiceProvider;
use Illuminate\View\Compilers\BladeCompiler;

class MoneyServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/config.php', 'money');
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([__DIR__.'/../config/config.php' => config_path('money.php')], 'config');
        }

        $this->callAfterResolving(BladeCompiler::class, function ($blade) {
            BladeExtension::register($blade);
        });
    }
}
