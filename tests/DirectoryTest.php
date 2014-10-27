<?php

class PluginInfoTest extends PHPUnit_Framework_TestCase
{
    public function testCheckVersionShouldFail()
    {
        $info = $this->getInfo();

        $this->assertEquals(false, $info->isCompatibleWith('4.1.0'));
    }

    public function testCheckVersionShouldSucceed()
    {
        $info = $this->getInfo();

        $this->assertEquals(true, $info->isCompatibleWith('4.2.0'));
    }

    public function testCheckVersionBlacklist()
    {
        $info = $this->getInfo();

        $this->assertEquals(false, $info->isCompatibleWith('4.1.4'));
    }

    public function testLabel()
    {
        $info = $this->getInfo();

        $this->assertEquals('Mehrfachänderung', $info->getLabel('de'));
        $this->assertEquals('Multiedit', $info->getLabel('en'));
    }

    public function testCopyright()
    {
        $info = $this->getInfo();

        $this->assertEquals('(c) by asd', $info->getCopyright());
    }

    public  function testLicense()
    {
        $info = $this->getInfo();

        $this->assertEquals('MIT 1234', $info->getLicense());
    }

    public function getLink()
    {
        $info = $this->getInfo();

        $this->assertEquals('http://wasdas.de', $info->getLink());
    }

    public function testAuthor()
    {
        $info = $this->getInfo();

        $this->assertEquals('Jon Doe', $info->getAuthor());
    }


    public function testCurrentVersion()
    {
        $info = $this->getInfo();

        $this->assertEquals('1.0.6', $info->getCurrentVersion());
    }



    /**
     * @return \PluginInfo\InfoDecorator
     */
    private function getInfo()
    {
        $infoService = new \Shopware\PluginInfo\PluginInfo(new \Shopware\PluginInfo\Backend\Directory());
        $info = $infoService->get(__DIR__ . '/assets/Backend/SwagTestPlugin');

        return $info;
    }
}