<?php
declare(strict_types=1);

namespace Infrastructure\Service\Collector;

use Atlas\Orm\Atlas;
use Infrastructure\Plugin\Configuration;
use Infrastructure\Plugin\Plugin;
use Infrastructure\Plugin\PluginInterface;

class PluginCollector extends Collector
{
    /**
     * @var array
     */
    private $alias = [];

    /**
     * @param Atlas $atlas
     */
    public function bootPlugins(Atlas $atlas): void
    {
        /**
         * @var string $name
         * @var PluginInterface $plugin
         */
        foreach ($this->bag as $name => &$plugin) {
            $config = new Configuration($plugin, $atlas);
            $config->loadConfig();
            $plugin->setConfig($config);
        }
    }

    /**
     * @param array $definition
     */
    public function addDefinition(array $definition): void
    {
        foreach ($definition as $plugin) {
            /** @var PluginInterface $instance */
            $instance = new $plugin;
            if (!$instance instanceof PluginInterface) {
                throw new \RuntimeException('Your plugin must implement '.Plugin::class.'.');
            }

            $this->bag[$plugin] = $instance;
            $this->alias[$instance->getAlias()] = $plugin;
        }
    }

    /**
     * @param string $name
     * @return PluginInterface|null
     */
    public function find(string $name): ?PluginInterface
    {
        $plugin = $this->bag[$name] ?? null;
        if (!$plugin) {
            return  $this->bag[$this->alias[$name]];
        }

        return $plugin;
    }
}