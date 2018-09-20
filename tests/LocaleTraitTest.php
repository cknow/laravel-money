<?php

namespace Cknow\Money;

/**
 * @covers \Cknow\Money\LocaleTrait
 */
class LocaleTraitTest extends \PHPUnit\Framework\TestCase
{
    public function testGetLocale()
    {
        static::assertEquals('en_US', LocaleTrait::getLocale());
    }

    public function testSetLocale()
    {
        LocaleTrait::setLocale('fr_FR');

        static::assertEquals('fr_FR', LocaleTrait::getLocale());
    }
}
