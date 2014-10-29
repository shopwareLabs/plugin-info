<?php

namespace Shopware\PluginInfo;

use Shopware\PluginInfo\Struct\Info;

/**
 * Class InfoDecorator
 * @package Shopware\PluginInfo
 */
class InfoDecorator
{
    /**
     * @var Struct\Info
     */
    private $info;

    public function __construct(Info $info)
    {
        $this->info = $info;
    }

    /**
     * @param string $language
     * @return string
     */
    public function getLabel($language = 'en')
    {
        $label = $this->info->label;

        if ($language === null) {
            return $label;
        }

        if (!isset($label[$language])) {
            throw new \OutOfRangeException("Label for $language not available");
        }

        return $label[$language];
    }

    /**
     * @return string
     */
    public function getCopyright()
    {
        return $this->info->copyright;
    }

    /**
     * @return array
     */
    public function getCompatibility()
    {
        return $this->info->compatibility;
    }

    /**
     * @param string $language
     * @return array
     */
    public function getChangelogs($language = null)
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

    /**
     * @param string $version
     * @param string $language
     * @return array
     */
    public function getChangelog($version, $language)
    {
        $changeLogs = $this->getChangelogs($language);

        if (!isset($changeLogs[$version])) {
            throw new \OutOfRangeException("Changelog for $language and version $version not available");
        }

        return $changeLogs[$version];
    }

    /**
     * @return array
     */
    public function getCompatibilities()
    {
        return $this->info->compatibility;
    }

    /**
     * @param string $version
     * @return bool
     */
    public function isCompatibleWith($version)
    {
        if ($this->getChangelogs() === null) {
            return true;
        }

        $minimum = $this->info->compatibility['minimumVersion'] ? : '0.0.0';
        $maximum = $this->info->compatibility['maximumVersion'] ? : '99.99.99';
        $blacklist = $this->info->compatibility['blacklist'] ? : array();

        /**
         * Will return true, if the requested version is
         * * ge minVersion
         * * le maxVersion
         * * not in blacklist array
         */

        return version_compare($version, $minimum, '>=') && version_compare($version, $maximum, '<=') && !in_array(
            $version,
            $blacklist
        );
    }

    /**
     * @return mixed
     */
    public function getLicense()
    {
        return $this->info->license;
    }

    public function getLink()
    {
        return $this->info->link;
    }

    public function getAuthor()
    {
        return $this->info->author;
    }

    public function getCurrentVersion()
    {
        return $this->info->currentVersion;
    }

    public function getInfo($language = null)
    {
        if ($language == null) {
            return $this->info->info;
        }

        if (!isset($this->info->info[$language])) {
            throw new \OutOfRangeException("info for $language not available");
        }

        return $this->info->info[$language];
    }

    public function getStoreDescription($language = null)
    {
        if ($language == null) {
            return $this->info->description;
        }

        if (!isset($this->info->description[$language])) {
            throw new \OutOfRangeException("store description for $language not available");
        }

        return $this->info->description[$language];
    }
}
