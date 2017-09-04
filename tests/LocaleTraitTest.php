<?php

namespace Cknow\Money;

/**
 * @covers \Cknow\Money\LocaleTrait
 */
class LocaleTraitTest extends \PHPUnit_Framework_TestCase
{
    public function testGetLocale()
    {
        $this->assertSame('pt_BR', LocaleTrait::getLocale());
    }

    public function testSetLocale()
    {
        LocaleTrait::setLocale('en_US');

        $this->assertSame('en_US', LocaleTrait::getLocale());
    }
}
