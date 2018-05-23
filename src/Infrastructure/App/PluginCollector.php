<?php
declare(strict_types=1);

namespace Infrastructure\App;

use Infrastructure\Plugin\Plugin;

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

            if (!$instance instanceof Plugin) {
                throw new \RuntimeException('Your plugin must implement '.Plugin::class.'.');
            }

            $instance->setTemplatePath($instance->getPath().'/templates');
            $instance->setConfigPath($instance->getPath().'/config');

            $this->plugins[$plugin] = $instance;
        }
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