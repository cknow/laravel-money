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
        static::assertEquals('en', Money::getLocale());
        static::assertEquals('USD', Money::getCurrency());
    }

    public function testBladeDirectives()
    {
        $customDirectives = $this->app->make('blade.compiler')->getCustomDirectives();

        static::assertArrayHasKey('money', $customDirectives);
        static::assertArrayHasKey('currency', $customDirectives);
    }

    protected function getServiceProviderClass($app)
    {
        return MoneyServiceProvider::class;
    }
}
