<?php

namespace Cknow\Money\Tests;

use Cknow\Money\MoneyServiceProvider;
use GrahamCampbell\TestBench\AbstractPackageTestCase;

class MoneyServiceProviderTest extends AbstractPackageTestCase
{
    public function testBladeDirectives()
    {
        $customDirectives = $this->app->make('blade.compiler')->getCustomDirectives();

        static::assertArrayHasKey('money', $customDirectives);
        static::assertArrayHasKey('currency', $customDirectives);
    }

    protected function getServiceProviderClass()
    {
        return MoneyServiceProvider::class;
    }
}
