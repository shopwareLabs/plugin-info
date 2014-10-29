<?php

namespace Shopware\PluginInfo;

use Shopware\PluginInfo\Backend\BackendInterface;
use Shopware\PluginInfo\Exceptions\ConstraintException;
use Shopware\PluginInfo\Struct\Info;

class PluginInfoHydrator
{
    /**
     * @param array $pluginData
     * @return Info
     * @throws ConstraintException
     */
    public function get($pluginData)
    {
        $struct = new Info();

        $struct->label = $this->prepareLabel($pluginData);
        $struct->currentVersion= $this->prepareCurrentVersion($pluginData);
        $struct->copyright = isset($pluginData['copyright']) ? $pluginData['copyright'] : null;
        $struct->link = isset($pluginData['link']) ? $pluginData['link'] : null;
        $struct->license = isset($pluginData['license']) ? $pluginData['license'] : null;
        $struct->author = isset($pluginData['author']) ? $pluginData['author'] : null;
        $struct->changelog = isset($pluginData['changelog']) ? $pluginData['changelog'] : array();
        $struct->compatibility = $this->prepareCompatibility($pluginData);

        $struct->description = isset($pluginData['description']) ? $pluginData['description'] : array();
        $struct->info = isset($pluginData['info']) ? $pluginData['info'] : array();

        return $struct;
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
