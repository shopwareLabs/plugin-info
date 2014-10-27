<?php

namespace Shopware\PluginInfo\Backend;

class Directory implements BackendInterface
{
    /**
     * @var
     */
    private $path;

    public function getPluginInfo()
    {
        $jsonPath = $this->path . DIRECTORY_SEPARATOR . 'plugin.json';
        if (!file_exists($jsonPath)) {
            throw new \RuntimeException("Cannot find $jsonPath");
        }

        return json_decode(file_get_contents($jsonPath), true);
    }

    public function setPlugin($plugin)
    {
        $path = rtrim($plugin, '/') . '/';
        $this->path = $plugin;
    }

    public function getInfo()
    {
        return array();
    }

    public function getDescription()
    {
        return array();
    }
}