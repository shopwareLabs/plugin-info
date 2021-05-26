<?php

declare(strict_types=1);
/**
 * (c) shopware AG <info@shopware.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Shopware\PluginInfo;

use Shopware\PluginInfo\Struct\Info;

class InfoDecorator
{
    /**
     * @var Info
     */
    private $info;

    public function __construct(Info $info)
    {
        $this->info = $info;
    }

    public function getLabel(?string $language = 'en'): string
    {
        $label = $this->info->label;

        if ($language === null) {
            return $label;
        }

        if (!isset($label[$language])) {
            throw new \OutOfRangeException(sprintf('Label for %s not available', $language));
        }

        return $label[$language];
    }

    public function getCopyright(): string
    {
        return $this->info->copyright;
    }

    public function getCompatibility(): array
    {
        return $this->info->compatibility;
    }

    public function getChangelogs(?string $language = null): array
    {
        $changeLogs = $this->info->changelog;

        if ($language === null) {
            return $changeLogs;
        }

        if (!isset($changeLogs[$language])) {
            throw new \OutOfRangeException("Changelog for $language not available");
        }

        return $changeLogs[$language];
    }

    public function getChangelog(string $version, string $language): string
    {
        $changeLogs = $this->getChangelogs($language);

        if (!isset($changeLogs[$version])) {
            throw new \OutOfRangeException("Changelog for $language and version $version not available");
        }

        return $changeLogs[$version];
    }

    public function getCompatibilities(): array
    {
        return $this->info->compatibility;
    }

    public function isCompatibleWith(string $version): bool
    {
        if ($this->getChangelogs() === null) {
            return true;
        }

        $minimum = $this->info->compatibility['minimumVersion'] ?: '0.0.0';
        $maximum = $this->info->compatibility['maximumVersion'] ?: '99.99.99';
        $blacklist = $this->info->compatibility['blacklist'] ?: [];

        /*
         * Will return true, if the requested version is
         * * ge minVersion
         * * le maxVersion
         * * not in blacklist array
         */

        return version_compare($version, $minimum, '>=')
            && version_compare($version, $maximum, '<=')
            && !\in_array($version, $blacklist);
    }

    public function getLicense(): string
    {
        return $this->info->license;
    }

    public function getLink(): array
    {
        return $this->info->link;
    }

    public function getAuthor(): string
    {
        return $this->info->author;
    }

    public function getCurrentVersion(): string
    {
        return $this->info->currentVersion;
    }

    public function getInfo($language = null): array
    {
        if ($language === null) {
            return $this->info->info;
        }

        if (!isset($this->info->info[$language])) {
            throw new \OutOfRangeException("info for $language not available");
        }

        return $this->info->info[$language];
    }

    public function getStoreDescription($language = null): array
    {
        if ($language === null) {
            return $this->info->description;
        }

        if (!isset($this->info->description[$language])) {
            throw new \OutOfRangeException("store description for $language not available");
        }

        return $this->info->description[$language];
    }
}
