<?php

namespace Shopware\PluginInfo\Backend;

class AutoDetect implements BackendInterface
{
    /**
     * @param mixed $plugin
     * @throws \RuntimeException
     * @return array
     */
    public function getPluginInfo($plugin)
    {
        switch (true) {
            case is_array($plugin);
                $backend = new ArrayTestCase();
                break;
            case is_dir($plugin):
                $backend = new Directory();
                break;
            case is_file($plugin):
                $backend = new Zip();
                break;
            default:
                throw new \RuntimeException("Could not automatically detect type of given plugin");
        }

        return $backend->getPluginInfo($plugin);
    }
}