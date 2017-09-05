<?php

namespace Cknow\Money;

use GrahamCampbell\TestBench\AbstractPackageTestCase;

/**
 * @covers \Cknow\Money\MoneyServiceProvider
 */
class MoneyServiceProviderTest extends AbstractPackageTestCase
{
    public function testLocale()
    {
        $this->assertEquals('en', Money::getLocale());
    }

    public function testBladeDirectives()
    {
        $customDirectives = $this->app->make('blade.compiler')->getCustomDirectives();

        $this->assertArrayHasKey('money', $customDirectives);
        $this->assertArrayHasKey('currency', $customDirectives);
    }

    protected function getServiceProviderClass($app)
    {
        return MoneyServiceProvider::class;
    }
}
