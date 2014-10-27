<?php

class ZipTest extends PHPUnit_Framework_TestCase
{

    public function testZipBackend()
    {
        $infoService = new \Shopware\PluginInfo\PluginInfo(new \Shopware\PluginInfo\Backend\Zip());
        $info = $infoService->get(__DIR__ . '/assets/SwagTestPlugin.zip');

        $this->assertEquals(true, $info->isCompatibleWith('4.2.0'));
    }
}