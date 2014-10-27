<?php

namespace Shopware\PluginInfo;

use Shopware\PluginInfo\Backend\BackendInterface;
use Shopware\PluginInfo\Exceptions\ConstraintException;
use Shopware\PluginInfo\Struct\Info;

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
        $json = $this->backend->getPluginInfo();

        $struct = new Info();

        $struct->label = $this->prepareLabel($json);
        $struct->currentVersion= $this->prepareCurrentVersion($json);
        $struct->copyright = isset($json['copyright']) ? $json['copyright'] : null;
        $struct->link = isset($json['link']) ? $json['link'] : null;
        $struct->license = isset($json['license']) ? $json['license'] : null;
        $struct->author = isset($json['author']) ? $json['author'] : null;
        $struct->changelog = isset($json['changelog']) ? $json['changelog'] : array();
        $struct->compatibility = $this->prepareCompatibility($json);
        $struct->description = $this->backend->getDescription();
        $struct->info = $this->backend->getInfo();

        return new InfoDecorator($struct);
    }

    private function prepareCurrentVersion($json)
    {
        if (!isset($json['currentVersion'])) {
            throw new ConstraintException('currentVersion is a required field');
        }

        return $json['currentVersion'];
    }

    private function prepareCompatibility($json)
    {
        $compatibility = isset($json['compatibility']) ? $json['compatibility'] : array();

        if (!isset($compatibility['minimumVersion'])) {
            $compatibility['minimumVersion'] = '0.0.0';
        }

        if (!isset($compatibility['maximumVersion'])) {
            $compatibility['maximumVersion'] = '99.99.99';
        }

        if (!isset($compatibility['blacklist'])) {
            $compatibility['blacklist'] = array();
        }

        return $compatibility;
    }

    private function prepareLabel($json)
    {
        if (!isset($json['label'])) {
            throw new ConstraintException("Field 'label' is required");
        }

        return $json['label'];
    }
}