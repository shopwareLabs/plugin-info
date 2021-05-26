<?php

declare(strict_types=1);
/**
 * (c) shopware AG <info@shopware.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Shopware\PluginInfo\Backend;

/**
 * Backend for plugin structures in directories
 */
class Directory implements BackendInterface
{
    /**
     * @param array|string $plugin
     *
     * @throws \RuntimeException
     */
    public function getPluginInfo($plugin): array
    {
        $path = rtrim($plugin, '/') . '/';

        $jsonPath = $path . DIRECTORY_SEPARATOR . 'plugin.json';
        if (!is_file($jsonPath)) {
            throw new \RuntimeException("Cannot find $jsonPath");
        }

        return json_decode(file_get_contents($jsonPath), true);
    }
}
