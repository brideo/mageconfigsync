<?php

namespace MageConfigSync\Reader;

use MageConfigSync\ConfigItem;
use MageConfigSync\ConfigItemSet;
use MageConfigSync\Exception\Reader\EnvironmentNotFound;
use Symfony\Component\Yaml\Yaml;

class YamlReader implements ReaderInterface
{
    /**
     * @var
     */
    private $fileName;

    /**
     * @var Yaml
     */
    private $yamlService;

    /**
     * @var
     */
    private $environment;

    /**
     * @var
     */
    private $fileData;

    public function __construct($fileName, Yaml $yamlService = null)
    {
        if ($yamlService === null) {
            $yamlService = new Yaml();
        }

        $this->fileName = $fileName;
        $this->yamlService = $yamlService;
    }

    /**
     * @param null $environment
     * @return ConfigItemSet
     * @throws EnvironmentNotFound
     */
    public function getConfigItemSet($environment = null)
    {
        $configSet = new ConfigItemSet();
        $fileData = $this->getFileData();

        $dataRoot = &$fileData;

        if ($environment) {
            if (!isset($fileData[$environment])) {
                throw new EnvironmentNotFound("Unable to find environment '$environment' in configuration");
            }

            $dataRoot = &$fileData[$environment];
        }

        foreach ($dataRoot as $scope => $configValues) {
            foreach ($configValues as $key => $value) {
                $configSet->add(
                    new ConfigItem($scope, $key, $value)
                );
            }
        }

        return $configSet;
    }

    /**
     * @return mixed
     */
    private function getFileData()
    {
        if ($this->fileData === null) {
            $this->fileData = $this->yamlService->parse($this->fileName);
        }

        return $this->fileData;
    }
}
