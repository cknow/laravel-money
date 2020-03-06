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
        $path = realpath(__DIR__.'/../config/config.php');

        $this->publishes([$path => config_path('money.php')], 'config');
        $this->mergeConfigFrom($path, 'money');

        if ('Illuminate\Foundation\Application' === get_class($this->app)) {
            BladeExtension::register($this->app->make('blade.compiler'));
        }

        Money::setLocale($this->app->make('config')->get('money.locale'));
        Money::setCurrency($this->app->make('config')->get('money.currency'));
    }
}
