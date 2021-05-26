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
 * Automatically detect the required backend
 */
class AutoDetect implements BackendInterface
{
    /**
     * @param array|string $plugin
     *
     * @throws \RuntimeException
     */
    public function getPluginInfo($plugin): array
    {
        switch (true) {
            case \is_array($plugin):
                $backend = new ArrayTestCase();
                break;
            case is_dir($plugin):
                $backend = new Directory();
                break;
            case is_file($plugin):
                $backend = new Zip();
                break;
            default:
                throw new \RuntimeException('Could not automatically detect type of given plugin');
        }

        return $backend->getPluginInfo($plugin);
    }
}
