<?php

namespace MageConfigSync;

use Countable;
use Iterator;
use Traversable;

class ConfigItemSet implements Countable, Iterator
{
    /**
     * @var ConfigItem[]
     */
    private $items = [];
    private $position = 0;

    /**
     * @param ConfigItem $configItem
     * @return $this
     */
    public function add(ConfigItem $configItem)
    {
        $this->items[] = $configItem;

        return $this;
    }

    /**
     * @return int
     */
    public function count()
    {
        return count($this->items);
    }

    /**
     * @return ConfigItem
     */
    public function current()
    {
        return $this->items[$this->key()];
    }

    public function next()
    {
        $this->position++;
    }

    public function key()
    {
        return $this->position;
    }

    public function valid()
    {
        return isset($this->items[$this->position]);
    }

    public function rewind()
    {
        $this->position = 0;
    }
}
