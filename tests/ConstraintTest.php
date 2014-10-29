<?php

class ConstraintTest extends PHPUnit_Framework_TestCase
{

    private function getBaseData()
    {
        return array(
            'label' => array(
                'en' => 'test',
                'de' => 'test'
            ),
            'currentVersion' => '1.0.0',
            'changelog' => array(
                'de' => array(
                    '1.0.0' => 'initial release'
                ),
                'en' => array(
                    '1.0.0' => 'initial release'
                )
            )
        );
    }

    public function testBaseData()
    {
        $info = new \Shopware\PluginInfo\PluginInfo(new \Shopware\PluginInfo\Backend\ArrayTestCase());
        $info = $info->get(
            array(
                'json' => $this->getBaseData()
            )
        );
    }

    /**
     * @expectedException \Shopware\PluginInfo\Exceptions\ValidatorException
     */
    public function testMissingLabel()
    {
        $baseData = $this->getBaseData();
        unset($baseData['label']);

        $info = new \Shopware\PluginInfo\PluginInfo(new \Shopware\PluginInfo\Backend\ArrayTestCase());
        $info = $info->get(
            array(
                'json' => $baseData
            )
        );

        $this->assertEquals('Hallo Welt', $info->getInfo());
    }

    /**
     * @expectedException \Shopware\PluginInfo\Exceptions\ValidatorException
     */
    public function testCurrentVersionMissing()
    {
        $baseData = $this->getBaseData();
        unset($baseData['changelog']);


        $info = new \Shopware\PluginInfo\PluginInfo(new \Shopware\PluginInfo\Backend\ArrayTestCase());
        $info = $info->get(
            array(
                'json' => $baseData
            )
        );
    }

    /**
     * @expectedException \OutOfRangeException
     */
    public function testMissingInfoLanguage()
    {
        $baseData = $this->getBaseData();

        $info = new \Shopware\PluginInfo\PluginInfo(new \Shopware\PluginInfo\Backend\ArrayTestCase());
        $info = $info->get(
            array(
                'json' => $baseData
            )
        );

        $info->getInfo('fr');
    }

    /**
     * @expectedException \OutOfRangeException
     */
    public function testMissingDescriptionLanguage()
    {
        $baseData = $this->getBaseData();


        $info = new \Shopware\PluginInfo\PluginInfo(new \Shopware\PluginInfo\Backend\ArrayTestCase());
        $info = $info->get(
            array(
                'json' => $baseData
            )
        );

        $info->getStoreDescription('fr');
    }
}