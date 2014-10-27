<?php

namespace PluginInfo\Backend;

class ArrayTestCase implements BackendInterface
{

    private $plugin;

    public function getPluginInfo()
    {
        return $this->plugin['json'];
    }

    public function setPlugin($plugin)
    {
        $this->plugin = $plugin;
    }

    public function getInfo()
    {
        return $this->plugin['description'];
    }

    public function getDescription()
    {
        return $this->plugin['info'];
    }
}