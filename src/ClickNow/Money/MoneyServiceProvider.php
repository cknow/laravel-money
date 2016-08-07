<?php

namespace ClickNow\Money;

use Illuminate\Support\ServiceProvider;

class MoneyServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot()
    {
        $config = __DIR__.'/../../config/money.php';

        $this->mergeConfigFrom($config, 'clicknow.money');

        $this->publishes([
            $config => $this->app->make('path.config').'/clicknow.money.php',
        ], 'config');

        Money::setLocale($this->app->make('translator')->getLocale());
        Currency::setCurrencies($this->app->make('config')->get('clicknow.money'));
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        BladeExtensions::register($this->app->make('view')->getEngineResolver()->resolve('blade')->getCompiler());
    }
}
