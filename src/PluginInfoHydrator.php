<?php

declare(strict_types=1);
/**
 * (c) shopware AG <info@shopware.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Shopware\PluginInfo;

use Shopware\PluginInfo\Exceptions\ConstraintException;
use Shopware\PluginInfo\Struct\Info;

class PluginInfoHydrator
{
    /**
     * @throws ConstraintException
     */
    public function get(array $pluginData): Info
    {
        $struct = new Info();

        $struct->label = $this->prepareLabel($pluginData);
        $struct->currentVersion = $this->prepareCurrentVersion($pluginData);
        $struct->copyright = $pluginData['copyright'] ?? null;
        $struct->link = $pluginData['link'] ?? null;
        $struct->license = $pluginData['license'] ?? null;
        $struct->author = $pluginData['author'] ?? null;
        $struct->changelog = $pluginData['changelog'] ?? [];
        $struct->compatibility = $this->prepareCompatibility($pluginData);

        $struct->description = $pluginData['description'] ?? [];
        $struct->info = $pluginData['info'] ?? [];

        return $struct;
    }

    private function prepareCurrentVersion(array $json): string
    {
        if (!isset($json['currentVersion'])) {
            throw new ConstraintException('currentVersion is a required field');
        }

        return $json['currentVersion'];
    }

    private function prepareCompatibility(array $json): array
    {
        $compatibility = $json['compatibility'] ?? [];

        if (!isset($compatibility['minimumVersion'])) {
            $compatibility['minimumVersion'] = '0.0.0';
        }

        if (!isset($compatibility['maximumVersion'])) {
            $compatibility['maximumVersion'] = '99.99.99';
        }

        if (!isset($compatibility['blacklist'])) {
            $compatibility['blacklist'] = [];
        }

        return $compatibility;
    }

    /**
     * @throws ConstraintException
     */
    private function prepareLabel(array $json): array
    {
        if (!isset($json['label'])) {
            throw new ConstraintException("Field 'label' is required");
        }

        return $json['label'];
    }
}
