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

        if (get_class($this->app) === 'Illuminate\Foundation\Application') {
            BladeExtension::register($this->app->make('blade.compiler'));
        }

        $config =$this->app->make('config');

        Money::setLocale($config->get('money.locale'));
        Money::setDefaultCurrency($config->get('money.defaultCurrency', $config->get('money.currency')));
        Money::setCurrencies($config->get('money.currencies', []));
    }
}
