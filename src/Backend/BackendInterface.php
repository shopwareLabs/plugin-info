<?php

namespace Shopware\PluginInfo\Backend;

interface BackendInterface
{
    /**
     * @param mixed $plugin
     * @return array
     */
    public function getPluginInfo($plugin);
}
