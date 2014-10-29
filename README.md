# Shopware plugin info

[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE.md)

The plugin info library allows you to analyze shopware plugin metadata of the new plugin.json metadata file. This way you can read versions or changelogs from a plugin without having to analyze the bootstrap file itself.

## Install

Via Composer

``` bash
$ composer require shopware-ag/plugin-info
```
## Usage

´´´
use Shopware\PluginInfo;
use Shopware\PluginInfo\Backend;

$info = new PluginInfo(new Zip());
$plugin = $info->get('plugin.zip');

$plugin->getCurrentVersion()         // 2.4.0
$plugin->isCompatibleWith('4.3.0')   // boolean
$plugin->getChangelogs()             // array of changelogs
$plugin->getChangelog('2.4.0', 'en') // english changelog for version 2.4.0
```