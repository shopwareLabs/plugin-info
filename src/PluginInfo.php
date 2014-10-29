<?php

namespace Shopware\PluginInfo;

use Shopware\PluginInfo\Backend\AutoDetect;
use Shopware\PluginInfo\Backend\BackendInterface;

use JsonSchema;
use Shopware\PluginInfo\Exceptions\ConstraintException;
use Shopware\PluginInfo\Exceptions\ValidatorException;

class PluginInfo
{
    /**
     * @var Backend\BackendInterface
     */
    private $backend;

    /**
     * @var PluginInfoHydrator
     */
    private $hydrator;

    /**
     * @param BackendInterface $backend
     */
    public function __construct(BackendInterface $backend = null)
    {
        if (!$backend) {
            $backend = new AutoDetect();
        }

        $this->backend = $backend;
        $this->hydrator = new PluginInfoHydrator();
    }

    /**
     * Depending on the backend, plugin could be a directory or zip
     *
     * @param string $plugin
     * @return InfoDecorator
     */
    public function get($plugin)
    {
        $pluginJson = $this->backend->getPluginInfo($plugin);

        $this->validate($pluginJson);

        $pluginStruct = $this->hydrator->get($pluginJson);

        return new InfoDecorator($pluginStruct);
    }

    /**
     * validate a given plugin object against the defined json format
     *
     * @param $json
     * @throws Exceptions\ValidatorException
     */
    private function validate($json)
    {
        $retriever = new JsonSchema\Uri\UriRetriever();
        $schema = $retriever->retrieve(
            realpath(__DIR__ . '/../res/plugin-info-schema.json'),
            'file://'
        );

        $validator = new JsonSchema\Validator();
        $validator->check(json_decode(json_encode($json)), $schema);

        if (!$validator->isValid()) {
            $errors = array_map(function($error) {
                return $error['property'] . ': ' . $error['message'];
            }, $validator->getErrors());
            throw new ValidatorException("Json not valid: " . implode(', ', $errors));
        }
    }
}
