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
        BladeExtension::register($this->app->make('blade.compiler'));
        Money::setLocale($this->app->make('translator')->getLocale());
    }
}
