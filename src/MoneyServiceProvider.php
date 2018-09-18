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
        
        Collection::macro('sumMoney', function ($callback) {
            $callback = $this->valueRetriever($callback);
            return $this->reduce(function (Money $result, $item) use ($callback) {
            if (!$amount = $callback($item)) {
                $amount = money('0');
            }
            return $result->add($amount);
            }, money('0'));
        });
    }
}
