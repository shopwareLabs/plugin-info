<?php

namespace Shopware\PluginInfo\Backend;

/**
 * Backend for plugin structures in directories
 *
 * Class Directory
 * @package Shopware\PluginInfo\Backend
 */
class Directory implements BackendInterface
{
    /**
     * {@inheritdoc}
     */
    public function getPluginInfo($plugin)
    {
        $path = rtrim($plugin, '/') . '/';

        $jsonPath = $path . DIRECTORY_SEPARATOR . 'plugin.json';
        if (!file_exists($jsonPath)) {
            throw new \RuntimeException("Cannot find $jsonPath");
        }

        return json_decode(file_get_contents($jsonPath), true);
    }
}
