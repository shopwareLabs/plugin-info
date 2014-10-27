<?php

namespace Shopware\PluginInfo;

use Shopware\PluginInfo\Backend\BackendInterface;

class PluginInfo
{
    /**
     * @var Backend\BackendInterface
     */
    private $backend;

    private $hydrator;

    public function __construct(BackendInterface $backend)
    {
        $this->backend = $backend;
        $this->hydrator = new PluginInfoHydrator($backend);
    }

    /**
     * Depending on the backend, plugin could be a directory or zip
     *
     * @param $plugin
     * @return InfoDecorator
     */
    public function get($plugin)
    {
        $this->backend->setPlugin($plugin);

        return $this->hydrator->get();
    }


}