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
use Shopware\PluginInfo\Backend\Directory;
use Shopware\PluginInfo\InfoDecorator;
use Shopware\PluginInfo\PluginInfo;

class DirectoryTest extends TestCase
{
    public function testCheckVersionShouldFail(): void
    {
        $info = $this->getInfo();

        static::assertEquals(false, $info->isCompatibleWith('4.1.0'));
    }

    public function testCheckVersionShouldSucceed(): void
    {
        $info = $this->getInfo();

        static::assertEquals(true, $info->isCompatibleWith('4.2.0'));
    }

    public function testCheckVersionBlacklist(): void
    {
        $info = $this->getInfo();

        static::assertEquals(false, $info->isCompatibleWith('4.1.4'));
    }

    public function testLabel(): void
    {
        $info = $this->getInfo();

        static::assertEquals('Mehrfachänderung', $info->getLabel('de'));
        static::assertEquals('Multiedit', $info->getLabel('en'));
    }

    public function testCopyright(): void
    {
        $info = $this->getInfo();

        static::assertEquals('(c) by asd', $info->getCopyright());
    }

    public function testCompatibility(): void
    {
        $info = $this->getInfo();

        static::assertEquals([
            'minimumVersion' => '4.1.3',
            'maximumVersion' => '99.99.99',
            'blacklist' => [0 => '4.1.4'],
        ], $info->getCompatibility());
    }

    public function testChangelogs(): void
    {
        $info = $this->getInfo();

        static::assertEquals([
            'de' => [
                    '1.0.6' => 'Korrigiert Installations-Probleme',
                ],
        ], $info->getChangelogs());
    }

    public function testChangelog(): void
    {
        $info = $this->getInfo();

        static::assertEquals('Korrigiert Installations-Probleme', $info->getChangelog('1.0.6', 'de'));
    }

    public function testLicense(): void
    {
        $info = $this->getInfo();

        static::assertEquals('MIT 1234', $info->getLicense());
    }

    public function getLink(): void
    {
        $info = $this->getInfo();

        static::assertEquals('http://wasdas.de', $info->getLink());
    }

    public function testAuthor(): void
    {
        $info = $this->getInfo();

        static::assertEquals('Jon Doe', $info->getAuthor());
    }

    public function testCurrentVersion(): void
    {
        $info = $this->getInfo();

        static::assertEquals('1.0.6', $info->getCurrentVersion());
    }

    private function getInfo(): InfoDecorator
    {
        return (new PluginInfo(new Directory()))->get(__DIR__ . '/assets/Backend/SwagTestPlugin');
    }
}
