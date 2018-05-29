<?php
declare(strict_types=1);

namespace Infrastructure\Service\Collector;

use Infrastructure\Plugin\Plugin;
use Infrastructure\Plugin\PluginInterface;

class PluginCollector extends Collector
{
    /**
     * @param string $definitionPath
     */
    public function addDefinition(string $definitionPath): void
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

            $this->bag[$instance->getName()] = $instance;
        }
    }

    /**
     * @param string $name
     * @return PluginInterface|null
     */
    public function find(string $name): ?PluginInterface
    {
        return $this->bag[$name] ?? null;
    }
}