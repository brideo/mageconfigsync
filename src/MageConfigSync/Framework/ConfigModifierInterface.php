<?php

namespace MageConfigSync\Framework;

interface ConfigModifierInterface
{
    /**
     * @param $scope
     * @param $path
     * @return mixed
     */
    public function deleteConfig($scope, $path);

    /**
     * @param $scope
     * @param $path
     * @param $value
     * @return mixed
     */
    public function updateConfig($scope, $path, $value);
}