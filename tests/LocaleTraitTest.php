<?php

namespace Cknow\Money;

/**
 * @covers \Cknow\Money\LocaleTrait
 */
class LocaleTraitTest extends \PHPUnit\Framework\TestCase
{
    public function testGetLocale()
    {
        $this->assertEquals('pt_BR', LocaleTrait::getLocale());
    }

    public function testSetLocale()
    {
        LocaleTrait::setLocale('en_US');

        $this->assertEquals('en_US', LocaleTrait::getLocale());
    }
}
