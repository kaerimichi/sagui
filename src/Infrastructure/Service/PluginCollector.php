<?php
declare(strict_types=1);

namespace Infrastructure\Service;

use Infrastructure\Plugin\Plugin;
use Infrastructure\Plugin\PluginInterface;

class PluginCollector implements \IteratorAggregate
{
    /**
     * @var array
     */
    private $plugins;

    /**
     * @param string $definitionPath
     * @throws \ReflectionException
     */
    public function load(string $definitionPath): void
    {
        if (!is_file($definitionPath)) {
            return;
        }

        /** @var array $plugins */
        $plugins = include $definitionPath;
        foreach ($plugins as $plugin) {
            $instance = new $plugin;

            if (!$instance instanceof PluginInterface) {
                throw new \RuntimeException('Your plugin must implement '.Plugin::class.'.');
            }

            $this->plugins[$instance->getName()] = $instance;
        }
    }

    /**
     * @param string $name
     * @return PluginInterface|null
     */
    public function find(string $name): ?PluginInterface
    {
        return $this->plugins[$name] ?? null;
    }

    public function getIterator()
    {
        return (function () {
            foreach ($this->plugins as $key => $val) {
                yield $key => $val;
            }
        })();
    }
}