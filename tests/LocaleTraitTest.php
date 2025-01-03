<?php

namespace Cknow\Money\Tests;

use Cknow\Money\LocaleTrait;

class LocaleTraitTest extends TestCase
{
    public function test_get_locale()
    {
        $mock = $this->getMockForTrait(LocaleTrait::class);

        static::assertEquals('en_US', $mock->getLocale());
    }

    public function test_set_locale()
    {
        $mock = $this->getMockForTrait(LocaleTrait::class);

        $mock->setLocale('fr_FR');

        static::assertEquals('fr_FR', $mock->getLocale());
    }
}
