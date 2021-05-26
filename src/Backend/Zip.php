<?php

declare(strict_types=1);
/**
 * (c) shopware AG <info@shopware.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Shopware\PluginInfo\Backend;

/**
 * Backend for zipped plugins
 */
class Zip implements BackendInterface
{
    /**
     * @param array|string $plugin
     *
     * @throws \RuntimeException
     */
    public function getPluginInfo($plugin): array
    {
        $zipArchive = $this->openZipArchive($plugin);

        if ($path = $this->searchForPluginJson($zipArchive)) {
            $jsonString = $this->getFileFromZip($path, $zipArchive);

            return json_decode($jsonString, true);
        } elseif ($path = $this->searchForPluginXml($zipArchive)) {
            $xmlString = $this->getFileFromZip($path, $zipArchive);

            return $this->readXml($xmlString);
        }
        throw new \RuntimeException('Plugin info file not found in zip (plugin.json or plugin.xml)');
    }

    private function openZipArchive(string $path): \ZipArchive
    {
        $zip = new \ZipArchive();
        if ($zip->open($path) !== true) {
            throw new \RuntimeException(sprintf('Could not open zip archive %s', $path));
        }

        return $zip;
    }

    private function getFileFromZip(string $filePath, \ZipArchive $zip): string
    {
        return $zip->getFromName($filePath);
    }

    /**
     * @return bool|string
     */
    private function searchForPluginJson(\ZipArchive $zip)
    {
        $filePath = preg_quote('plugin.json');

        $pattern = sprintf('#(?P<namespace>Backend|Core|Frontend)/(?P<name>.*)/(?P<file>%s)#i', $filePath);

        for ($i = 0; $i < $zip->numFiles; ++$i) {
            $filename = $zip->getNameIndex($i);
            if (preg_match($pattern, $filename)) {
                return $filename;
            }
        }

        return false;
    }

    /**
     * @return bool|string
     */
    private function searchForPluginXml(\ZipArchive $zip)
    {
        $filePath = preg_quote('plugin.xml');

        $pattern = sprintf('#(?P<name>.*)/(?P<file>%s)#i', $filePath);

        for ($i = 0; $i < $zip->numFiles; ++$i) {
            $filename = $zip->getNameIndex($i);
            if (preg_match($pattern, $filename)) {
                return $filename;
            }
        }

        return false;
    }

    private function readXml(string $data): array
    {
        $dom = new \DOMDocument();
        $dom->loadXML($data);

        return $this->parseInfo($dom);
    }

    private function parseInfo(\DOMDocument $xml): array
    {
        $entries = (new \DOMXPath($xml))->query('//plugin');
        if ($entries === false) {
            return [];
        }

        $entry = $entries[0];
        $info = [];

        foreach ($this->getChildren($entry, 'label') as $label) {
            $lang = ($label->getAttribute('lang')) ?: 'en';
            $info['label'][$lang] = $label->nodeValue;
        }

        foreach ($this->getChildren($entry, 'description') as $description) {
            $lang = ($description->getAttribute('lang')) ?: 'en';
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
                $lang = ($changes->getAttribute('lang')) ?: 'en';
                $info['changelog'][$lang][$version] = $changes->nodeValue;
            }
        }

        $compatibility = $this->getFirstChild($entry, 'compatibility');
        if ($compatibility) {
            $info['compatibility'] = [
                'minimumVersion' => $compatibility->getAttribute('minVersion'),
                'maximumVersion' => $compatibility->getAttribute('maxVersion'),
                'blacklist' => $this->getChildrenValues($compatibility, 'blacklist'),
            ];
        }

        return $info;
    }

    /**
     * Get child elements by name.
     *
     * @return \DOMElement[]
     */
    private function getChildren(\DOMNode $node, string $name): array
    {
        $children = [];
        foreach ($node->childNodes as $child) {
            if ($child instanceof \DOMElement && $child->localName === $name) {
                $children[] = $child;
            }
        }

        return $children;
    }

    private function getFirstChild(\DOMNode $node, string $name): ?\DOMElement
    {
        if ($children = $this->getChildren($node, $name)) {
            return $children[0];
        }

        return null;
    }

    /**
     * Get child element values by name.
     *
     * @return \DOMElement[]
     */
    private function getChildrenValues(\DOMNode $node, string $name): array
    {
        $children = [];
        foreach ($node->childNodes as $child) {
            if ($child instanceof \DOMElement && $child->localName === $name) {
                $children[] = $child->nodeValue;
            }
        }

        return $children;
    }
}
