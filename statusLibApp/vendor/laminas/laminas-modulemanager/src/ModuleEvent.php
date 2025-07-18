<?php

/**
 * @see       https://github.com/laminas/laminas-modulemanager for the canonical source repository
 * @copyright https://github.com/laminas/laminas-modulemanager/blob/master/COPYRIGHT.md
 * @license   https://github.com/laminas/laminas-modulemanager/blob/master/LICENSE.md New BSD License
 */

namespace Laminas\ModuleManager;

use Laminas\EventManager\Event;

/**
 * Custom event for use with module manager
 * Composes Module objects
 *
 * @method ModuleManager getTarget
 */
class ModuleEvent extends Event
{
    /**
     * Module events triggered by eventmanager
     */
    const EVENT_MERGE_CONFIG        = 'mergeConfig';
    const EVENT_LOAD_MODULES        = 'loadModules';
    const EVENT_LOAD_MODULE_RESOLVE = 'loadModule.resolve';
    const EVENT_LOAD_MODULE         = 'loadModule';
    const EVENT_LOAD_MODULES_POST   = 'loadModules.post';

    /**
     * @var mixed
     */
    protected $module;

    /**
     * @var string
     */
    protected $moduleName;

    /**
     * @var Listener\ConfigMergerInterface
     */
    protected $configListener;

    /**
     * Get the name of a given module
     *
     * @return string
     */
    public function getModuleName()
    {
        return $this->moduleName;
    }

    /**
     * Set the name of a given module
     *
     * @param  string $moduleName
     * @throws Exception\InvalidArgumentException
     * @return ModuleEvent
     */
    public function setModuleName($moduleName)
    {
        if (!is_string($moduleName)) {
            throw new Exception\InvalidArgumentException(
                sprintf(
                    '%s expects a string as an argument; %s provided',
                    __METHOD__,
                    gettype($moduleName)
                )
            );
        }
        // Performance tweak, don't add it as param.
        $this->moduleName = $moduleName;

        return $this;
    }

    /**
     * Get module object
     *
     * @return null|object
     */
    public function getModule()
    {
        return $this->module;
    }

    /**
     * Set module object to compose in this event
     *
     * @param  object $module
     * @throws Exception\InvalidArgumentException
     * @return ModuleEvent
     */
    public function setModule($module)
    {
        if (!is_object($module)) {
            throw new Exception\InvalidArgumentException(
                sprintf(
                    '%s expects a module object as an argument; %s provided',
                    __METHOD__,
                    gettype($module)
                )
            );
        }
        // Performance tweak, don't add it as param.
        $this->module = $module;

        return $this;
    }

    /**
     * Get the config listener
     *
     * @return null|Listener\ConfigMergerInterface
     */
    public function getConfigListener()
    {
        return $this->configListener;
    }

    /**
     * Set module object to compose in this event
     *
     * @param  Listener\ConfigMergerInterface $configListener
     * @return ModuleEvent
     */
    public function setConfigListener(Listener\ConfigMergerInterface $configListener)
    {
        $this->setParam('configListener', $configListener);
        $this->configListener = $configListener;

        return $this;
    }
}
