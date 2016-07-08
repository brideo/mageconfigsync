<?php

namespace MageConfigSync\Framework;

use Meanbee\LibMageConf\ConfigReader;
use PDO;
use PDOStatement;

class Magento1 implements ConfigModifierInterface
{
    /**
     * @var ConfigReader
     */
    private $configReader;

    public function __construct(ConfigReader $configReader)
    {
        $this->configReader = $configReader;
    }

    public function deleteConfig($scope, $path)
    {
        $statement = $this->getDeleteStatement();

        $statement->bindParam(':scope', $this->getScopeName($scope), PDO::PARAM_STR);
        $statement->bindParam(':scope_id', $this->getScopeId($scope), PDO::PARAM_INT);
        $statement ->bindParam(':path', $path, PDO::PARAM_STR);

        return $statement->execute();
    }

    public function updateConfig($scope, $path, $value)
    {
        $statement = $this->getUpdateStatement();

        $statement->bindParam(':scope', $this->getScopeName($scope), PDO::PARAM_STR);
        $statement->bindParam(':scope_id', $this->getScopeId($scope), PDO::PARAM_INT);
        $statement ->bindParam(':path', $path, PDO::PARAM_STR);
        $statement ->bindParam(':value', $value, PDO::PARAM_STR);

        return $statement->execute();
    }

    /**
     * @param $scope
     * @return mixed
     */
    protected function getScopeName($scope)
    {
        $scopeParts = explode('-', $scope);

        if (count($scopeParts) == 2) {
            return $scopeParts[0];
        }

        return 'default';
    }

    /**
     * @param $scope
     * @return int
     */
    protected function getScopeId($scope)
    {
        $scopeParts = explode('-', $scope);

        if (count($scopeParts) == 2) {
            return (int) $scopeParts[1];
        }

        return 0;
    }

    /**
     * @return PDO
     */
    protected function getDb()
    {
        return new PDO(
            sprintf(
                "mysql:host=%s;dbname=%s",
                $this->configReader->getDatabaseHost(),
                $this->configReader->getDatabaseName()
            ),
            $this->configReader->getDatabaseUsername(),
            $this->configReader->getDatabasePassword()
        );
    }

    /**
     * @return PDOStatement
     */
    protected function getDeleteStatement()
    {
        return $this->getDb()->prepare(
            "DELETE FROM core_config_data WHERE scope = :scope AND scope_id = :scope_id AND path = :path"
        );
    }

    /**
     * @return PDOStatement
     */
    protected function getUpdateStatement()
    {
        return $this->getDb()->prepare("
            INSERT INTO core_config_data (scope, scope_id, path, value)
            VALUES (:scope, :scope_id, :path, :value)
            ON DUPLICATE KEY UPDATE value = :value
        ");
    }
}