# Shopware plugin info

[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE.md)

The plugin info library allows you to analyze shopware plugin metadata of the new plugin.json metadata file. This way you can read versions or changelogs from a plugin without having to analyze the bootstrap file itself.

## Install

Via Composer

``` bash
$ composer require shopwarelabs/plugin-info
```
## Usage

```
use Shopware\PluginInfo;
use Shopware\PluginInfo\Backend;

$info = new PluginInfo(new Zip());
$plugin = $info->get('plugin.zip');

$plugin->getCurrentVersion()         // 2.4.0
$plugin->isCompatibleWith('4.3.0')   // boolean
$plugin->getChangelogs()             // array of changelogs
$plugin->getChangelog('2.4.0', 'en') // english changelog for version 2.4.0
```

## plugin.json
The plugin.json file should be placed in the same folder as the Bootstrap.php.

It looks like this:

```
{
    "label": {
        "de": "German label of the plugin",
        "en": "English label of the plugin"
    },
    "copyright": "(c) by me",
    "license": "MIT",
    "link": "http://plugin-homepage-or-store-link.de",
    "author": "Jon Doe",
    "currentVersion": "1.0.6",

    "changelogs": {
        "de": {
            "1.0.6": "German  changelog"
        },
        "en": {
            "1.0.6": "English changelog"
        }
    },

    "compatibility": {
        "minimumVersion": "4.1.3",
        "maximumVersion": null,
        "blacklist": [
            "4.1.4"
        ]
    }
}
```
