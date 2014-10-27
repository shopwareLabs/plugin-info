<?php

namespace Shopware\PluginInfo\Backend;

class Zip implements BackendInterface
{
    /**
     * @var
     */
    private $path;

    private $zip;

    private function getZip()
    {
        if (!$this->zip) {
            $this->zip = new \ZipArchive();
            if ($this->zip->open($this->path) !== true) {
                throw new \RuntimeException("Could not open zip archive {$this->path}");
            }
        }

        return $this->zip;
    }

    private function getFilesFromZip($file)
    {
        $zip = $this->getZip();

        $pattern = "#(?P<namespace>Backend|Core|Frontend)/(?P<name>.*)/(?P<file>{$file})#i";

        $result = array();
        for ($i = 0; $i < $zip->numFiles; $i++) {
            $filename = $zip->getNameIndex($i);

            $matches = array();
            if (preg_match($pattern, $filename, $matches)) {
                $result[] = $filename;
            }
        }

        return $result;

    }

    public function getPluginInfo()
    {
        $path = $this->getFilesFromZip(preg_quote('plugin.json'));

        if (!$path = array_pop($path)) {
            throw new \RuntimeException("plugin.json not found");
        }

        return json_decode($this->getZip()->getFromName($path), true);
    }

    public function setPlugin($plugin)
    {
        $path = rtrim($plugin, '/') . '/';
        $this->path = $plugin;
    }

    public function getInfo()
    {
        return array();
    }

    public function getDescription()
    {
        return array();
    }
}