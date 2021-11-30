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
     * @return void
     */
    protected function getEnvironmentSetUp($app)
    {
        $app->config->set('money.locale', 'en_US');

        parent::getEnvironmentSetUp($app);
    }

    /**
     * Get the service provider class.
     *
     * @return string
     */
    protected function getServiceProviderClass()
    {
        return MoneyServiceProvider::class;
    }
}
