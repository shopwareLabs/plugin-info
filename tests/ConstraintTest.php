<?php

class ConstraintTest extends PHPUnit_Framework_TestCase
{

    /**
     * @expectedException \PluginInfo\Exceptions\ConstraintException
     */
    public function testMissingLabel()
    {
        $info = new \PluginInfo\PluginInfo(new \PluginInfo\Backend\ArrayTestCase());
        $info = $info->get(
            array(
                'json' => array(),
            )
        );

        $this->assertEquals('Hallo Welt', $info->getInfo());
    }

    /**
     * @expectedException \PluginInfo\Exceptions\ConstraintException
     */
    public function testCurrentVersionMissing()
    {
        $info = new \PluginInfo\PluginInfo(new \PluginInfo\Backend\ArrayTestCase());
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
        $info = new \PluginInfo\PluginInfo(new \PluginInfo\Backend\ArrayTestCase());
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
        $info = new \PluginInfo\PluginInfo(new \PluginInfo\Backend\ArrayTestCase());
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