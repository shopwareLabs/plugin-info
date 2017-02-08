<?php

namespace Shopware\PluginInfo\Backend;

/**
 * Backend for zipped plugins
 *
 * Class Zip
 *
 * @package Shopware\PluginInfo\Backend
 */
class Zip implements BackendInterface
{
    /**
     * {@inheritdoc}
     * @throws \RuntimeException
     */
    public function getPluginInfo($plugin)
    {
        $zipArchive = $this->openZipArchive($plugin);

        if ($path = $this->searchForPluginJson($zipArchive)) {
            $jsonString = $this->getFileFromZip($path, $zipArchive);
            return json_decode($jsonString, true);
        } elseif ($path = $this->searchForPluginXml($zipArchive)) {
            $xmlString = $this->getFileFromZip($path, $zipArchive);
            return $this->readXml($xmlString);
        } else {
            throw new \RuntimeException('Plugin info file not found in zip (plugin.json or plugin.xml)');
        }
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
     * @param \ZipArchive $zip
     * @return bool|string
     */
    private function searchForPluginJson(\ZipArchive $zip)
    {
        $filePath = preg_quote('plugin.json');

        $pattern = "#(?P<namespace>Backend|Core|Frontend)/(?P<name>.*)/(?P<file>{$filePath})#i";

        for ($i = 0; $i < $zip->numFiles; $i++) {
            $filename = $zip->getNameIndex($i);
            if (preg_match($pattern, $filename)) {
                return $filename;
            }
        }

        return false;
    }

    /**
     * @param \ZipArchive $zip
     * @return bool|string
     */
    private function searchForPluginXml(\ZipArchive $zip)
    {
        $filePath = preg_quote('plugin.xml');

        $pattern = "#(?P<name>.*)/(?P<file>{$filePath})#i";

        for ($i = 0; $i < $zip->numFiles; $i++) {
            $filename = $zip->getNameIndex($i);
            if (preg_match($pattern, $filename)) {
                return $filename;
            }
        }

        return false;
    }

    /**
     * @param string $data
     * @return array
     */
    private function readXml($data)
    {
        $dom = new \DOMDocument();
        $dom->loadXML($data);

        return $this->parseInfo($dom);
    }

    /**
     * @param \DOMDocument $xml
     * @return array
     */
    private function parseInfo(\DOMDocument $xml)
    {
        $xpath = new \DOMXPath($xml);

        if (false === $entries = $xpath->query('//plugin')) {
            return;
        }

        $entry = $entries[0];
        $info = [];

        foreach ($this->getChildren($entry, 'label') as $label) {
            $lang = ($label->getAttribute('lang')) ? $label->getAttribute('lang') : 'en';
            $info['label'][$lang] = $label->nodeValue;
        }

        foreach ($this->getChildren($entry, 'description') as $description) {
            $lang = ($description->getAttribute('lang')) ? $description->getAttribute('lang') : 'en';
            $info['description'][$lang] = trim($description->nodeValue);
        }

        $simpleKeys = ['version', 'license', 'author', 'copyright', 'link'];
        foreach ($simpleKeys as $simpleKey) {
            if ($names = $this->getChildren($entry, $simpleKey)) {
                if ($simpleKey === 'version') {
                    $info['currentVersion'] = $names[0]->nodeValue;
                } else {
                    $info[$simpleKey] = $names[0]->nodeValue;
                }
            }
        }

        foreach ($this->getChildren($entry, 'changelog') as $changelog) {
            $version = $changelog->getAttribute('version');

            foreach ($this->getChildren($changelog, 'changes') as $changes) {
                $lang = ($changes->getAttribute('lang')) ? $changes->getAttribute('lang') : 'en';
                $info['changelog'][$lang][$version] = $changes->nodeValue;
            }
        }

        $compatibility = $this->getFirstChild($entry, 'compatibility');
        if ($compatibility) {
            $info['compatibility'] = [
                'minimumVersion' => $compatibility->getAttribute('minVersion'),
                'maximumVersion' => $compatibility->getAttribute('maxVersion'),
                'blacklist' => $this->getChildrenValues($compatibility, 'blacklist')
            ];
        }

        return $info;
    }

    /**
     * Get child elements by name.
     *
     * @param \DOMNode $node
     * @param mixed    $name
     *
     * @return \DOMElement[]
     */
    private function getChildren(\DOMNode $node, $name)
    {
        $children = array();
        foreach ($node->childNodes as $child) {
            if ($child instanceof \DOMElement && $child->localName === $name) {
                $children[] = $child;
            }
        }

        return $children;
    }

    /**
     * @param \DOMNode $node
     * @param $name
     * @return null|\DOMElement
     */
    private function getFirstChild(\DOMNode $node, $name)
    {
        if ($children = $this->getChildren($node, $name)) {
            return $children[0];
        }

        return null;
    }

    /**
     * Get child element values by name.
     *
     * @param \DOMNode $node
     * @param mixed    $name
     *
     * @return \DOMElement[]
     */
    private function getChildrenValues(\DOMNode $node, $name)
    {
        $children = array();
        foreach ($node->childNodes as $child) {
            if ($child instanceof \DOMElement && $child->localName === $name) {
                $children[] = $child->nodeValue;
            }
        }

        return $children;
    }
}
