<?php

namespace MageConfigSync;

use MageConfigSync\Framework\ConfigModifierInterface;

class ConfigManager
{
    /**
     * @var ConfigModifierInterface
     */
    private $magento;

    public function __construct(ConfigModifierInterface $magento)
    {
        $this->magento = $magento;
    }

    /**
     * @param ConfigItemSet $configSet
     */
    public function applyConfigItemSet(ConfigItemSet $configSet)
    {
        $taskList = [];

        foreach ($configSet as $configItem) {
            if ($configItem->isDelete()) {
                array_unshift($taskList, $configItem);
            } else {
                array_push($taskList, $configItem);
            }
        }

        foreach ($taskList as $configItem) {
            $this->applyConfigItem($configItem);
        }
    }

    public function applyConfigItem(ConfigItem $configItem)
    {
        if ($configItem->isDelete()) {
            $this->magento->deleteConfig($configItem->getScope(), $configItem->getKey());
        } else {
            $this->magento->updateConfig($configItem->getScope(), $configItem->getKey(), $configItem->getValue());
        }
    }
}