<?php

class ConstraintTest extends PHPUnit_Framework_TestCase
{

    /**
     * @expectedException \Shopware\PluginInfo\Exceptions\ConstraintException
     */
    public function testMissingLabel()
    {
        $info = new \Shopware\PluginInfo\PluginInfo(new \Shopware\PluginInfo\Backend\ArrayTestCase());
        $info = $info->get(
            array(
                'json' => array(),
            )
        );

        $this->assertEquals('Hallo Welt', $info->getInfo());
    }

    /**
     * @expectedException \Shopware\PluginInfo\Exceptions\ConstraintException
     */
    public function testCurrentVersionMissing()
    {
        $info = new \Shopware\PluginInfo\PluginInfo(new \Shopware\PluginInfo\Backend\ArrayTestCase());
        $info = $info->get(
            array(
                'json' => array('label' => 'Hallo Welt')
            )
        );

        $info->getInfo('fr');
    }

    /**
     * @expectedException \OutOfRangeException
     */
    public function testMissingInfoLanguage()
    {
        $info = new \Shopware\PluginInfo\PluginInfo(new \Shopware\PluginInfo\Backend\ArrayTestCase());
        $info = $info->get(
            array(
                'json' => array('label' => 'Hallo Welt', 'currentVersion' => array('test')),
                'info' => array('de' => 'Hallo Welt', 'en' => 'Hello world'),
                'description' => array('de' => 'Hallo Welt', 'en' => 'Hello world')
            )
        );

        $info->getInfo('fr');
    }

    /**
     * @expectedException \OutOfRangeException
     */
    public function testMissingDescriptionLanguage()
    {
        $info = new \Shopware\PluginInfo\PluginInfo(new \Shopware\PluginInfo\Backend\ArrayTestCase());
        $info = $info->get(
            array(
                'json' => array('label' => 'Hallo Welt', 'currentVersion' => array('test')),
                'info' => array('de' => 'Hallo Welt', 'en' => 'Hello world'),
                'description' => array('de' => 'Hallo Welt', 'en' => 'Hello world')
            )
        );

        $info->getInfo('fr');
    }
}