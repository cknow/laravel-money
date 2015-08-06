<?php

namespace Clicknow\Money;

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
        $config = __DIR__.'/../../resources/config/money.php';

        $this->mergeConfigFrom($config, 'clicknow.money');

        $this->publishes([
            $config => $this->app->make('path.config').'/clicknow.money.php',
        ]);

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
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return ['clicknow.money'];
    }
}
