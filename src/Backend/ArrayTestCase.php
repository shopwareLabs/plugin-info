<?php

namespace Shopware\PluginInfo\Backend;

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
