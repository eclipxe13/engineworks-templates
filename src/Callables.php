<?php

declare(strict_types=1);

namespace EngineWorks\Templates;

use Countable;

class Callables implements Countable
{
    /**
     * @var array<string, callable>
     */
    private $map = [];

    /**
     * Add a function callable to the collection
     */
    public function add(string $name, callable $callable): void
    {
        $this->map[$name] = $callable;
    }

    /**
     * Return a callable based on the string name
     * If the name is not registered then return NULL
     */
    public function get(string $name): ?callable
    {
        return $this->map[$name] ?? null;
    }

    /**
     * Return TRUE if the name is registered
     */
    public function exists(string $name): bool
    {
        return isset($this->map[$name]);
    }

    /**
     * Remove a registered name from the collection
     */
    public function remove(string $name): void
    {
        unset($this->map[$name]);
    }

    /**
     * Call a function by name
     *
     * @param array<mixed> $arguments
     */
    public function call(string $name, array $arguments): string
    {
        /** @var callable():string|null $callable */
        $callable = $this->get($name);
        if (null === $callable) {
            return '';
        }

        $return = call_user_func_array($callable, $arguments);

        if (is_object($return) && is_callable([$return, '__toString'])) {
            $return = (string) $return;
        } elseif (is_scalar($return)) {
            $return = (string) $return;
        }

        return is_string($return) ? $return : '';
    }

    /**
     * Return the names of the registered functions
     *
     * @return string[]
     */
    public function names(): array
    {
        return array_keys($this->map);
    }

    /**
     * Attach an array of plugins. Is a shortcut to call several times to attatch method
     * If an element of the array is not a plugin then the element is discarded without notice
     *
     * @param Plugin[] $plugins
     */
    public function attachAll(array $plugins): void
    {
        $this->attach(...$plugins);
    }

    /**
     * Attach all the functions offered by the plugin
     *
     * @param Plugin ...$plugins
     */
    public function attach(Plugin ...$plugins): void
    {
        foreach ($plugins as $plugin) {
            $this->attachPlugin($plugin);
        }
    }

    /**
     * Attach all the functions offered by the plugin
     *
     * @param Plugin $plugin
     */
    public function attachPlugin(Plugin $plugin): void
    {
        foreach ($plugin->getCallablesTable() as $name => $methodName) {
            /** @var callable():string $callable */
            $callable = [$plugin, $methodName];
            $this->add($name, $callable);
        }
    }

    /**
     * Detatch all the functions offered by the plugins
     */
    public function detach(Plugin ...$plugins): void
    {
        foreach ($plugins as $plugin) {
            $this->detachPlugin($plugin);
        }
    }

    /**
     * Detatch all the functions offered by the plugin
     *
     * @param Plugin $plugin
     */
    public function detachPlugin(Plugin $plugin): void
    {
        foreach ($plugin->getCallablesTable() as $name => $_) {
            $this->remove($name);
        }
    }

    public function count(): int
    {
        return count($this->map);
    }
}
