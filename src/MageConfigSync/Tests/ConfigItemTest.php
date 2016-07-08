<?php


namespace MageConfigSync\Tests;

use MageConfigSync\ConfigItem;
use MageConfigSync\Magento;

class ConfigItemTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function testAccessors()
    {
        $item = new ConfigItem("default", "hello", "world");

        $this->assertEquals("default", $item->getScope());
        $this->assertEquals("hello", $item->getKey());
        $this->assertEquals("world", $item->getValue());
    }
}