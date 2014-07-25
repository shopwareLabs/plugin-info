<?php

namespace PluginInfo;

use PluginInfo\Struct\Info;

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

    public function getCopyright()
    {
        return $this->info->copyright;
    }

    public function getCompatibility()
    {
        return $this->info->compatibility;
    }

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

    public function getChangelog($version, $language = null)
    {
        $changeLogs = $this->getChangelogs($language);

        if (!isset($changeLogs[$version])) {
            throw new \OutOfRangeException("Changelog for $language and version $version not available");
        }

        return $changeLogs[$version];
    }

    public function getCompatibilities()
    {
        return $this->info->compatibility;
    }

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
        return version_compare($version, $minimum, '>=')
            && version_compare($version, $maximum, '<=')
            && !in_array($version, $blacklist);
    }


    public function getInfo()
    {

    }

    public function getStoreDescription()
    {

    }
}