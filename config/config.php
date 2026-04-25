<?php

return [
    /*
     |--------------------------------------------------------------------------
     | Laravel money
     |--------------------------------------------------------------------------
     */
    'locale' => config('app.locale', 'en_US'),
    'defaultCurrency' => config('app.currency', 'USD'),
    'defaultFormatter' => null,
    'defaultSerializer' => null,
    'isoCurrenciesPath' => is_dir(__DIR__.'/../vendor')
        ? __DIR__.'/../vendor/moneyphp/money/resources/currency.php'
        : __DIR__.'/../../../moneyphp/money/resources/currency.php',
    'cryptoCurrenciesPath' => is_dir(__DIR__.'/../vendor')
        ? __DIR__.'/../vendor/moneyphp/money/resources/binance.php'
        : __DIR__.'/../../../moneyphp/money/resources/binance.php',
    'currencies' => [
        'iso' => 'all',
        'bitcoin' => 'all',
        'crypto' => 'all',
        'custom' => [
            // 'MY1' => 2,
            // 'MY2' => 3
        ],
    ],
];
