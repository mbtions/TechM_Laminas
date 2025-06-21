<?php

/**
 * @see       https://github.com/laminas-api-tools/statuslib-example for the canonical source repository
 * @copyright https://github.com/laminas-api-tools/statuslib-example/blob/master/COPYRIGHT.md
 * @license   https://github.com/laminas-api-tools/statuslib-example/blob/master/LICENSE.md New BSD License
 */

namespace StatusLib;

use DomainException;
use Laminas\ApiTools\Configuration\ConfigResource;
use Laminas\Config\Writer\PhpArray as ConfigWriter;

/**
 * Service factory for the ArrayMapper
 * 
 * Requires the Config service in the service locator, and a
 * statuslib.array_mapper_path subkey within the configuration that points
 * to a valid filesystem path of a PHP file that will return an array.
 *
 * Passes the data from the file, the path to the file, and a PhpArray config
 * writer to a Laminas\ApiTools\Configuration\ConfigResource instance, and passes the data
 * and the ConfigResource instance to the ArrayMapper.
 */
class ArrayMapperFactory
{
    public function __invoke($services)
    {
        if (!$services->has('Config')) {
            throw new DomainException('Cannot create StatusLib\ArrayMapper; missing Config dependency');
        }

        $config = $services->get('Config');
        if (! isset($config['statuslib']['array_mapper_path'])) {
            throw new DomainException('Cannot create StatusLib\ArrayMapper; missing statuslib.array_mapper_path configuration');
        }

        $path = $config['statuslib']['array_mapper_path'];
        if (! file_exists($path)) {
            throw new DomainException(sprintf(
                'Cannot create StatusLib\ArrayMapper; path "%s" does not exist',
                $path
            ));
        }

        $data = include $path;

        if (! is_array($data)) {
            throw new DomainException(sprintf(
                'Cannot create StatusLib\ArrayMapper; file "%s" does not return an array',
                $path
            ));
        }

        $configResource = new ConfigResource($data, realpath($path), new ConfigWriter());
        return new ArrayMapper($data, $configResource);
    }
}
