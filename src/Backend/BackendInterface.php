<?php

declare(strict_types=1);
/**
 * (c) shopware AG <info@shopware.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Shopware\PluginInfo\Backend;

interface BackendInterface
{
    /**
     * @param array|string $plugin
     */
    public function getPluginInfo($plugin): array;
}
