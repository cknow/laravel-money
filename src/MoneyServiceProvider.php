<?php

namespace Cknow\Money;

use Illuminate\Support\ServiceProvider;

class MoneyServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application events.
     */
    public function boot()
    {
        BladeExtensions::register($this->app->make('view')->getEngineResolver()->resolve('blade')->getCompiler());
        Money::setLocale($this->app->make('translator')->getLocale());
    }
}
