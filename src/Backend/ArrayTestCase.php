<?php

namespace Shopware\PluginInfo\Backend;

/**
 * Array backend for test cases
 *
 * Class ArrayTestCase
 * @package Shopware\PluginInfo\Backend
 */
class ArrayTestCase implements BackendInterface
{
    /**
     * {@inheritdoc}
     */
    public function getPluginInfo($plugin)
    {
        return $plugin['json'];
    }
}
