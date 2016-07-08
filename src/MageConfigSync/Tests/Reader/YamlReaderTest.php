<?php

namespace MageConfigSync\Tests\Reader;

use MageConfigSync\Reader\YamlReader;
use VirtualFileSystem\FileSystem;

class YamlReaderTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var FileSystem
     */
    private $fs;

    public function setUp()
    {
        $this->fs = new FileSystem();
    }

    /**
     * @test
     */
    public function testNoEnvironment()
    {
        $file = $this->makeFileFromFixture('noEnvironment');
        $reader = new YamlReader($file);

        $this->assertCount(2, $reader->getConfigItemSet());
    }

    /**
     * @test
     */
    public function testEnvironment()
    {
        $file = $this->makeFileFromFixture('withEnvironments');
        $reader = new YamlReader($file);

        $this->assertCount(1, $reader->getConfigItemSet("production"));
        $this->assertCount(3, $reader->getConfigItemSet("development"));
    }

    /**
     * @test
     * @expectedException \MageConfigSync\Exception\Reader\EnvironmentNotFound
     */
    public function testEnvironmentNotFoundWithEnvironmentFile()
    {
        $file = $this->makeFileFromFixture('withEnvironments');
        $reader = new YamlReader($file);

        $reader->getConfigItemSet("not_found");
    }

    /**
     * @test
     * @expectedException \MageConfigSync\Exception\Reader\EnvironmentNotFound
     */
    public function testEnvironmentNotFoundWithNoEnvironmentsInFile()
    {
        $file = $this->makeFileFromFixture('noEnvironment');
        $reader = new YamlReader($file);

        $reader->getConfigItemSet("not_found");
    }

    /**
     * @return FileSystem
     */
    protected function fs()
    {
        return $this->fs;
    }

    /**
     * @param string $fileContents
     * @return string
     */
    protected function makeFile($fileContents = '')
    {
        $fileName = sprintf("/file_%d.test", rand(1000000000, 9999999999));
        $this->fs()->createFile($fileName, $fileContents);

        return $this->fs()->path($fileName);
    }

    /**
     * @param $fixtureName
     * @return string
     */
    protected function makeFileFromFixture($fixtureName)
    {
        $fixturePath = sprintf("%s/fixtures/%s.yml", __DIR__, $fixtureName);
        $fixtureContent = file_get_contents($fixturePath);

        return $this->makeFile($fixtureContent);
    }
}