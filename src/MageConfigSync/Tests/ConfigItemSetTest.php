<?php

namespace MageConfigSync\Tests;

use MageConfigSync\ConfigItem;
use MageConfigSync\ConfigItemSet;
use MageConfigSync\Magento;

class ConfigItemSetTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function testCountable()
    {
        $configSet = new ConfigItemSet();

        $this->assertCount(0, $configSet);

        $configSet->add($this->makeConfigItem());
        $configSet->add($this->makeConfigItem());

        $this->assertCount(2, $configSet);

        $configSet->add($this->makeConfigItem());

        $this->assertCount(3, $configSet);

        $configSet->add($this->makeConfigItem());
        $configSet->add($this->makeConfigItem());

        $this->assertCount(5, $configSet);
    }

    protected function makeConfigItem()
    {
        return new ConfigItem("default", "hello", "world");
    }
}