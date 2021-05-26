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
use Shopware\PluginInfo\Backend\ArrayTestCase;
use Shopware\PluginInfo\Exceptions\ValidatorException;
use Shopware\PluginInfo\InfoDecorator;
use Shopware\PluginInfo\PluginInfo;

class ConstraintTest extends TestCase
{
    private function getBaseData(): array
    {
        return [
            'label' => [
                'en' => 'test',
                'de' => 'test',
            ],
            'currentVersion' => '1.0.0',
            'changelog' => [
                'de' => [
                    '1.0.0' => 'initial release',
                ],
                'en' => [
                    '1.0.0' => 'initial release',
                ],
            ],
        ];
    }

    public function testBaseData(): void
    {
        $info = new PluginInfo(new ArrayTestCase());
        $info = $info->get(
            [
                'json' => $this->getBaseData(),
            ]
        );

        static::assertInstanceOf(InfoDecorator::class, $info);
    }

    public function testMissingLabel(): void
    {
        $this->expectException(ValidatorException::class);
        $baseData = $this->getBaseData();
        unset($baseData['label']);

        $info = new PluginInfo(new ArrayTestCase());
        $info = $info->get(
            [
                'json' => $baseData,
            ]
        );

        static::assertEquals('Hallo Welt', $info->getInfo());
    }

    public function testCurrentVersionMissing(): void
    {
        $this->expectException(ValidatorException::class);
        $baseData = $this->getBaseData();
        unset($baseData['changelog']);

        $info = new PluginInfo(new ArrayTestCase());
        $info = $info->get(
            [
                'json' => $baseData,
            ]
        );
    }

    public function testMissingInfoLanguage(): void
    {
        $this->expectException(\OutOfRangeException::class);
        $baseData = $this->getBaseData();

        $info = new PluginInfo(new ArrayTestCase());
        $info = $info->get(
            [
                'json' => $baseData,
            ]
        );

        $info->getInfo('fr');
    }

    public function testMissingDescriptionLanguage(): void
    {
        $this->expectException(\OutOfRangeException::class);
        $baseData = $this->getBaseData();

        $info = new PluginInfo(new ArrayTestCase());
        $info = $info->get(
            [
                'json' => $baseData,
            ]
        );

        $info->getStoreDescription('fr');
    }
}
