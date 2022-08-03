<?php
namespace EngineWorks\Templates;

class Callables implements \Countable
{
    private $map = [];

    /**
     * Add a function callable to the collection
     *
     * @param string $name
     * @param callable $callable
     */
    public function add($name, callable $callable)
    {
        $this->map[$name] = $callable;
    }

    /**
     * Return a callable based on the string name
     * If the name is not registered then return NULL
     *
     * @param string $name
     * @return callable|null
     */
    public function get($name)
    {
        return array_key_exists($name, $this->map) ? $this->map[$name] : null;
    }

    /**
     * Return TRUE if the name is registered
     *
     * @param string $name
     * @return bool
     */
    public function exists($name)
    {
        return array_key_exists($name, $this->map);
    }

    /**
     * Remove a registered name from the collection
     *
     * @param $name
     */
    public function remove($name)
    {
        unset($this->map[$name]);
    }

    /**
     * Call a function by name
     *
     * @param string $name
     * @param array $arguments
     * @return string
     */
    public function call($name, array $arguments)
    {
        $callable = $this->get($name);
        return (null !== $callable) ? call_user_func_array($callable, $arguments) : '';
    }

    /**
     * Return the names of the registered functions
     *
     * @return array
     */
    public function names()
    {
        return array_keys($this->map);
    }

    /**
     * Attach an array of plugins. Is a shortcut to call several times to attatch method
     * If an element of the array is not a plugin then the element is discarded without notice
     *
     * @param Plugin[] $plugins
     */
    public function attachAll(array $plugins)
    {
        foreach ($plugins as $plugin) {
            if (! ($plugin instanceof Plugin)) {
                continue;
            }
            $this->attach($plugin);
        }
    }

    /**
     * Attach all the functions offered by the plugin
     *
     * @param Plugin $plugin
     */
    public function attach(Plugin $plugin)
    {
        foreach ($plugin->getCallablesTable() as $name => $callable) {
            $this->add($name, [$plugin, $callable]);
        }
    }

    /**
     * Detatch all the functions offered by the plugin
     *
     * @param Plugin $plugin
     */
    public function detach(Plugin $plugin)
    {
        foreach (array_keys($plugin->getCallablesTable()) as $name) {
            $this->remove($name);
        }
    }

    public function count(): int
    {
        return count($this->map);
    }
}
