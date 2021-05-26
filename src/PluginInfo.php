<?php

declare(strict_types=1);
/**
 * (c) shopware AG <info@shopware.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Shopware\PluginInfo;

use JsonSchema\Uri\UriRetriever;
use JsonSchema\Validator;
use Shopware\PluginInfo\Backend\AutoDetect;
use Shopware\PluginInfo\Backend\BackendInterface;
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
     * @param string|array $plugin
     *
     * @throws ConstraintException
     * @throws ValidatorException
     */
    public function get($plugin): InfoDecorator
    {
        $pluginJson = $this->backend->getPluginInfo($plugin);

        $this->validate($pluginJson);

        $pluginStruct = $this->hydrator->get($pluginJson);

        return new InfoDecorator($pluginStruct);
    }

    /**
     * validate a given plugin object against the defined json format
     *
     * @throws ValidatorException
     */
    private function validate(array $json): void
    {
        $retriever = new UriRetriever();

        // Detect phar archives - they don't need the file:// prefix
        $prefix = strpos(__DIR__, 'phar://') === 0 ? '' : 'file://';

        $schema = $retriever->retrieve(
            $prefix . __DIR__ . '/../res/plugin-info-schema.json'
        );

        $validator = new Validator();
        $validator->check(json_decode(json_encode($json)), $schema);

        if (!$validator->isValid()) {
            $errors = array_map(function ($error) {
                return $error['property'] . ': ' . $error['message'];
            }, $validator->getErrors());
            throw new ValidatorException('Json not valid: ' . implode(', ', $errors));
        }
    }
}
