<?php

namespace Cknow\Money\Tests;

use Cknow\Money\MoneyServiceProvider;
use GrahamCampbell\TestBench\AbstractPackageTestCase;

abstract class TestCase extends AbstractPackageTestCase
{
    /**
     * Setup the application environment.
     *
     * @param  \Illuminate\Contracts\Foundation\Application  $app
     */
    protected function getEnvironmentSetUp($app): void
    {
        $app->config->set('money.locale', 'en_US');

        parent::getEnvironmentSetUp($app);
    }

    /**
     * Get the service provider class.
     */
    protected static function getServiceProviderClass(): string
    {
        return MoneyServiceProvider::class;
    }
}
