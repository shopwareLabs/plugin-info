<?php

declare(strict_types=1);
/**
 * (c) shopware AG <info@shopware.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Shopware\PluginInfo\Test;

use PHPUnit\Framework\TestCase;
use Shopware\PluginInfo\Backend\Zip;
use Shopware\PluginInfo\PluginInfo;

class ZipTest extends TestCase
{
    public function testZipBackend(): void
    {
        $infoService = new PluginInfo(new Zip());
        $info = $infoService->get(__DIR__ . '/assets/SwagTestPlugin.zip');

        static::assertEquals(true, $info->isCompatibleWith('4.2.0'));
    }

    public function testZipBackendNewPluginStructure(): void
    {
        $infoService = new PluginInfo(new Zip());
        $info = $infoService->get(__DIR__ . '/assets/SwagNewPlugin.zip');

        static::assertEquals(true, $info->isCompatibleWith('5.2.12'));
    }
}
