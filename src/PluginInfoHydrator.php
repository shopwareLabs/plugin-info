<?php

namespace PluginInfo;

use PluginInfo\Backend\BackendInterface;
use PluginInfo\Struct\Info;

class PluginInfoHydrator
{
    /**
     * @var Backend\BackendInterface
     */
    private $backend;

    public function __construct(BackendInterface $backend)
    {
        $this->backend = $backend;
    }

    public function get()
    {
        $json = $this->backend->getPluginJson();

        $struct = new Info();

        $struct->label = $json['label'];
        $struct->copyright = $json['copyright'];
        $struct->link = $json['link'];
        $struct->author = $json['author'];
        $struct->changelog = $json['changelogs'];
        $struct->compatibility = $json['compatibility'];
        $struct->description = $this->backend->getDescription();
        $struct->info = $this->backend->getInfo();

        return new InfoDecorator($struct);
    }
}