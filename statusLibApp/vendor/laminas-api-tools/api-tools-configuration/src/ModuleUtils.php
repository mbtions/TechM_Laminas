<?php

/**
 * @see       https://github.com/laminas-api-tools/api-tools-configuration for the canonical source repository
 * @copyright https://github.com/laminas-api-tools/api-tools-configuration/blob/master/COPYRIGHT.md
 * @license   https://github.com/laminas-api-tools/api-tools-configuration/blob/master/LICENSE.md New BSD License
 */

namespace Laminas\ApiTools\Configuration;

use Laminas\ModuleManager\ModuleManager;
use ReflectionObject;

class ModuleUtils
{
    /**
     * @var ModuleManager
     */
    protected $modules = [];

    /**
     * @var array
     */
    protected $moduleData = [];

    /**
     * @param  ModuleManager $modules
     */
    public function __construct(ModuleManager $modules)
    {
        $this->modules = $modules->getLoadedModules();
    }

    /**
     * Retrieve the path to the module
     *
     * @param  string $moduleName
     * @return string
     * @throws Exception\InvalidArgumentException if module does not exist
     * @throws Exception\RuntimeException if unable to locate module path
     */
    public function getModulePath($moduleName)
    {
        $moduleName = $this->normalizeModuleName($moduleName);
        if (isset($this->moduleData[$moduleName])
            && isset($this->moduleData[$moduleName]['path'])
        ) {
            return $this->moduleData[$moduleName]['path'];
        }

        $this->validateModule($moduleName);

        $this->deriveModuleData($moduleName);
        return $this->moduleData[$moduleName]['path'];
    }

    /**
     * Retrieve the path to the module configuration
     *
     * @param  string $moduleName
     * @return string
     * @throws Exception\InvalidArgumentException if module does not exist
     * @throws Exception\RuntimeException if unable to locate config path
     */
    public function getModuleConfigPath($moduleName)
    {
        $moduleName = $this->normalizeModuleName($moduleName);
        if (isset($this->moduleData[$moduleName])
            && isset($this->moduleData[$moduleName]['config'])
        ) {
            return $this->moduleData[$moduleName]['config'];
        }

        $this->validateModule($moduleName);

        $this->deriveModuleData($moduleName);
        return $this->moduleData[$moduleName]['config'];
    }

    /**
     * Validate that the module actually exists
     *
     * @param  string $moduleName
     * @throws Exception\InvalidArgumentException if themodule does not exist
     */
    protected function validateModule($moduleName)
    {
        if (!array_key_exists($moduleName, $this->modules)) {
            throw new Exception\InvalidArgumentException(sprintf(
                'The module specified, "%s", does not exist; cannot retrieve module data',
                $moduleName
            ));
        }
    }

    /**
     * Derive all module data from module name provided
     *
     * @param  string $moduleName
     */
    protected function deriveModuleData($moduleName)
    {
        $configPath = $this->deriveModuleConfig($moduleName);
        $modulePath = dirname(dirname($configPath));
        $this->moduleData[$moduleName] = [
            'config' => $configPath,
            'path'   => $modulePath,
        ];
    }

    /**
     * Determines the location of the module configuration file
     *
     * @param  string $moduleName
     * @return string
     * @throws Exception\RuntimeException if unable to find the configuration file
     */
    protected function deriveModuleConfig($moduleName)
    {
        $moduleClassPath = $this->getModuleClassPath($moduleName);
        $configPath      = $this->recurseTree($moduleClassPath);

        if (false === $configPath) {
            throw new Exception\RuntimeException(sprintf(
                'Unable to determine configuration path for module "%s"',
                $moduleName
            ));
        }

        return $configPath;
    }

    /**
     * Derives the module class's filesystem location
     *
     * @param  string $moduleName
     * @return string
     */
    protected function getModuleClassPath($moduleName)
    {
        $module   = $this->modules[$moduleName];
        $r        = new ReflectionObject($module);
        $fileName = $r->getFileName();
        return dirname($fileName);
    }

    /**
     * Recurse upwards through a tree to find the module configuration file
     *
     * @param  string $path
     * @return false|string
     */
    protected function recurseTree($path)
    {
        if (!is_dir($path)) {
            return false;
        }

        if (file_exists($path . '/config/module.config.php')) {
            return $path . '/config/module.config.php';
        }

        if (strtoupper(substr(PHP_OS, 0, 3)) !== 'WIN'
            && (in_array($path, ['.', '/', '\\\\', '\\'])
                || preg_match('#[a-z]:(\\\\|/{1,2})$#i', $path))
        ) {
            // Don't recurse past the root
            return false;
        }

        return $this->recurseTree(dirname($path));
    }

    /**
     * Normalize the module name
     *
     * @param  string $moduleName
     * @return string
     */
    protected function normalizeModuleName($moduleName)
    {
        return str_replace(['.', '/'], '\\', $moduleName);
    }
}
