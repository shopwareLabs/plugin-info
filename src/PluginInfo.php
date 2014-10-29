<?php

namespace Shopware\PluginInfo;

use Shopware\PluginInfo\Backend\BackendInterface;

class PluginInfo
{
    /**
     * @var Backend\BackendInterface
     */
    private $backend;

    /**
     * @var PluginInfoHydrator
     */
    private $hydrator;

    /**
     * @param BackendInterface $backend
     */
    public function __construct(BackendInterface $backend)
    {
        $this->backend = $backend;
        $this->hydrator = new PluginInfoHydrator();
    }

    /**
     * Depending on the backend, plugin could be a directory or zip
     *
     * @param string $plugin
     * @return InfoDecorator
     */
    public function get($plugin)
    {
        $pluginJson = $this->backend->getPluginInfo($plugin);
        $pluginStruct = $this->hydrator->get($pluginJson);

        return new InfoDecorator($pluginStruct);
    }
}
