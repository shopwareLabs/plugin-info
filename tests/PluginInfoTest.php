<?php

class PluginInfoTest extends PHPUnit_Framework_TestCase
{
    public function testCheckVersion()
    {
        $infoService = new \PluginInfo\PluginInfo(new \PluginInfo\Backend\Directory());
        $info = $infoService->get(__DIR__ . '/assets/SwagTestPlugin');

        $this->assertEquals(false, $info->isCompatibleWith('4.1.0'));
    }
}