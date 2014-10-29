<?php

class AutoDetectTest extends PHPUnit_Framework_TestCase
{

    public function testZip()
    {
        $infoService = new \Shopware\PluginInfo\PluginInfo();
        $info = $infoService->get(__DIR__ . '/assets/SwagTestPlugin.zip');

        $this->assertEquals(true, $info->isCompatibleWith('4.2.0'));
    }

    public function testDirectory()
    {
        $infoService = new \Shopware\PluginInfo\PluginInfo();
        $info = $infoService->get(__DIR__ . '/assets/Backend/SwagTestPlugin');

        $this->assertEquals(true, $info->isCompatibleWith('4.2.0'));
    }

    public function testArray()
    {
        $infoService = new \Shopware\PluginInfo\PluginInfo();
        $info = $infoService->get(array(
            "json" => array(
                'label' => array(
                    'de' => 'test',
                    'en' => 'test'
                ),
                'changelog' => array(
                    'de' => array(
                        '1.0.0' => 'test'
                    )
                ),
                'currentVersion' => '1.0.0'
            )
        ));

        $this->assertEquals(true, $info->isCompatibleWith('4.2.0'));
    }
}