<?php

namespace MageConfigSync\Reader;

use MageConfigSync\ConfigItemSet;

interface ReaderInterface
{
    /**
     * @return ConfigItemSet
     */
    public function getConfigItemSet();
}
