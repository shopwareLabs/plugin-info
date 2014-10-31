<?php

namespace Shopware\PluginInfo\Backend;

/**
 * Backend for zipped plugins
 *
 * Class Zip
 * @package Shopware\PluginInfo\Backend
 */
class Zip implements BackendInterface
{
    /**
     * {@inheritdoc}
     */
    public function getPluginInfo($plugin)
    {
        $zipArchive = $this->openZipArchive($plugin);

        $path = $this->searchFileInZip('plugin.json', $zipArchive);
        $data = $this->getFileFromZip($path, $zipArchive);

        return json_decode($data, true);
    }

    /**
     * @param string $path
     * @return \ZipArchive
     */
    private function openZipArchive($path)
    {
        $zip = new \ZipArchive();
        if ($zip->open($path) !== true) {
            throw new \RuntimeException("Could not open zip archive {$path}");
        }

        return $zip;
    }

    /**
     * @param string $filePath
     * @param \ZipArchive $zip
     * @return string
     */
    private function getFileFromZip($filePath, \ZipArchive $zip)
    {
        return $zip->getFromName($filePath);
    }

    /**
     * @param $filePath
     * @param \ZipArchive $zip
     * @return string
     */
    private function searchFileInZip($filePath, \ZipArchive $zip)
    {
        $filePath = preg_quote($filePath);

        $pattern = "#(?P<namespace>Backend|Core|Frontend)/(?P<name>.*)/(?P<file>{$filePath})#i";

        for ($i = 0; $i < $zip->numFiles; $i++) {
            $filename = $zip->getNameIndex($i);
            if (preg_match($pattern, $filename)) {
                return $filename;
            }
        }

        throw new \RuntimeException(sprintf(
            "File %s not found in zip",
            $filePath
        ));
    }
}
