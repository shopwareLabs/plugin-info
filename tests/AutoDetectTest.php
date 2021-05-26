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
use Shopware\PluginInfo\PluginInfo;

class AutoDetectTest extends TestCase
{
    public function testZip(): void
    {
        $infoService = new PluginInfo();
        $info = $infoService->get(__DIR__ . '/assets/SwagTestPlugin.zip');

        static::assertEquals(true, $info->isCompatibleWith('4.2.0'));
    }

    public function testDirectory(): void
    {
        $infoService = new PluginInfo();
        $info = $infoService->get(__DIR__ . '/assets/Backend/SwagTestPlugin');

        static::assertEquals(true, $info->isCompatibleWith('4.2.0'));
    }

    public function testArray(): void
    {
        $infoService = new PluginInfo();
        $info = $infoService->get([
            'json' => [
                'label' => [
                    'de' => 'test',
                    'en' => 'test',
                ],
                'changelog' => [
                    'de' => [
                        '1.0.0' => 'test',
                    ],
                ],
                'currentVersion' => '1.0.0',
            ],
        ]);

        static::assertEquals(true, $info->isCompatibleWith('4.2.0'));
    }
}
